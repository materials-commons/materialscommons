<?php

namespace App\Imports\Etl;

use App\Actions\Activities\CreateActivityAction;
use App\Actions\Entities\CreateEntityAction;
use App\Actions\Etl\GetFileByPathAction;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class EntityActivityImporter
{
    private $projectId;
    private $experimentId;
    private $userId;

    private $sawHeader = false;
    private $headerTracker;
    private $worksheet;
    private $entityTracker;
    private $activityTracker;
    private $rowNumber;
    private $rows;
    private $currentSheetRows;

    public function __construct($projectId, $experimentId, $userId)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->rowNumber = 0;
        $this->rows = collect();
        $this->currentSheetRows = collect();

        $this->entityTracker = new EntityTracker();
        $this->activityTracker = new HashedActivityTracker();
    }

    public function execute($spreadsheetPath)
    {
        $reader = new Xlsx();
        $reader->setReadEmptyCells(true);
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($spreadsheetPath);
        $worksheets = $spreadsheet->getAllSheets();
        $blankRowCount = 0;
        foreach ($worksheets as $worksheet) {
            $this->beforeSheet($worksheet);
            foreach ($worksheet->getRowIterator() as $row) {
                if (!$this->onRow($row)) {
                    // saw blank row
                    $blankRowCount++;
                } else {
                    // Not a blank row so reset blankRowCount
                    $blankRowCount = 0;
                }
                if ($blankRowCount >= 10) {
                    // when we've seen 10 or more consecutive blank rows then
                    // we stop processing data
                    break;
                }
            }
            $this->afterSheet();
        }
        $this->afterImport();
    }

    private function beforeSheet($worksheet)
    {
        $this->sawHeader = false;
        $this->headerTracker = new HeaderTracker();
        $this->worksheet = $worksheet;
        $this->rowNumber = 0;
        $this->currentSheetRows = collect();
    }

    private function afterSheet()
    {
        $this->currentSheetRows->each(function (RowTracker $row) {
            if (!$this->entityTracker->hasEntity($row->entityName)) {
                $this->addNewEntity($row);
            } else {
                $this->addToExistingEntity($row);
            }
        });
    }

    private function afterImport()
    {
        // All sheets processed and loaded, now build relationships
        // from parent column.
        $this->createActivityRelationships();
    }

    private function onRow(Row $row)
    {
        if (!$this->sawHeader) {
            $this->processHeader($row);
            return true;
        } else {
            return $this->processSample($row);
        }
    }

    public function getHeaders()
    {
        return $this->headerTracker;
    }

    private function processHeader(Row $row)
    {
        $index = 0;
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach ($cellIterator as $cell) {
            if ($index > 1) {
                $value = $cell->getValue();
                if ($value !== null) {
                    $ah = AttributeHeader::parse($value);
                    $this->headerTracker->addHeader($ah);
                }
            }
            $index++;
        }

        $this->sawHeader = true;
    }

    // Process a sample in the worksheet
    private function processSample(Row $row)
    {
        $rowTracker = new RowTracker($this->rowNumber, $this->worksheet->getTitle());
        $rowTracker->loadRow($row, $this->headerTracker);
        if ($rowTracker->entityName == "") {
            return false;
        }
        $this->rows->push($rowTracker);
        $this->currentSheetRows->push($rowTracker);
        return true;
    }

    private function addNewEntity(RowTracker $row)
    {
        $createEntityAction = new CreateEntityAction();
        $entity = $createEntityAction([
            'name'          => $row->entityName,
            'project_id'    => $this->projectId,
            'experiment_id' => $this->experimentId,
        ], $this->userId);
        $this->entityTracker->addEntity($entity);
        $state = $entity->entityStates()->first();
        $this->addAttributesToEntity($row->entityAttributes, $entity, $state);
        // Add a new activity
        $activity = $this->addNewActivity($entity, $state, $row);
        $this->activityTracker->addActivity($row->activityAttributesHash, $activity);
        $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $state, $activity);
    }

    private function addAttributesToEntity(Collection $entityAttributes, Entity $entity, EntityState $state)
    {
        $seenAttributes = collect();
        $entityAttributes->each(function ($attr) use ($state, $seenAttributes, $entity) {
            if ($seenAttributes->has($attr->name)) {
                $a = $seenAttributes->get($attr->name);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $attr->unit,
                    'val'          => ['value' => $attr->value],
                ]);
            } else {
                $a = Attribute::create([
                    'name'              => $attr->name,
                    'attributable_id'   => $state->id,
                    'attributable_type' => EntityState::class,
                ]);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $attr->unit,
                    'val'          => ['value' => $attr->value],
                ]);
                $seenAttributes->put($attr->name, $a);
            }
        });
    }

    private function addFilesToActivityAndEntity(Collection $fileAttributes, Entity $entity, EntityState $entityState,
        Activity $activity)
    {
        $getFileByPathAction = new GetFileByPathAction();
        $fileAttributes->each(function (ColumnAttribute $attr) use (
            $getFileByPathAction,
            $entity,
            $entityState,
            $activity
        ) {
            $header = $this->headerTracker->getHeaderByIndex($attr->columnNumber - 2);
            $path = "{$header->name}/{$attr->value}";
            if (strpos($attr->value, "/") !== false) {
                // Cell contains the path, no need to use the header
                $path = $attr->value;
            }
            $file = $getFileByPathAction($this->projectId, $path);
            if ($file !== null) {
                $activity->files()->attach([$file->id => ['direction' => 'in']]);
            }
        });
    }

    private function addToExistingEntity(RowTracker $row)
    {
        $activity = $this->activityTracker->getActivity($row->activityAttributesHash);
        if ($activity === null) {
            // There is no matching activity so we need to do the following
            // 1. Create a new entity state and add it to the entity
            // 2. Add all entity attributes to that entity state
            // 3. Create the new activity and associate it with that entity/entity state.
            $entity = $this->entityTracker->getEntity($row->entityName);
            $state = EntityState::create([
                'owner_id'  => $this->userId,
                'entity_id' => $entity->id,
                'current'   => true,
            ]);
            $this->addAttributesToEntity($row->entityAttributes, $entity, $state);
            $activity = $this->addNewActivity($entity, $state, $row);
            $this->activityTracker->addActivity($row->activityAttributesHash, $activity);
            $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $state, $activity);
        } else {
            // Matching activity found, so add attribute values as additional values on existing
            // measurements for this entity. To do this first get the entity state that is associated
            // with the entity for this activity. Then add the attributes.
            $entity = $this->entityTracker->getEntity($row->entityName);
            $entityStates = $activity->entityStates()->get();
            $entityState = $entityStates->firstWhere('entity_id', $entity->id);
            if (is_null($entityState)) {
                return;
            }
            $this->addValuesToEntityStateAttributes($row->entityAttributes, $entityState);
            $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $entityState, $activity);
        }
    }

    private function addValuesToEntityStateAttributes(Collection $attributes, EntityState $entityState)
    {
        $attributes->each(function (ColumnAttribute $attr) use ($entityState) {
            $a = Attribute::where('name', $attr->name)->where('attributable_type', EntityState::class)
                          ->where('attributable_id', $entityState->id)->first();
            AttributeValue::create([
                'attribute_id' => $a->id,
                'unit'         => $attr->unit,
                'val'          => ['value' => $attr->value],
            ]);
        });
    }

    private function addNewActivity(Entity $entity, EntityState $entityState, RowTracker $rowTracker)
    {
        $createActivityAction = new CreateActivityAction();
        $attributes = $rowTracker->activityAttributes->map(function (ColumnAttribute $attr) {
            return [
                'name'  => $attr->name,
                'value' => $attr->value,
                'unit'  => $attr->unit,
            ];
        })->toArray();

        $activity = $createActivityAction([
            'name'          => $rowTracker->activityName,
            'project_id'    => $this->projectId,
            'experiment_id' => $this->experimentId,
            'attributes'    => $attributes,
        ], $this->userId);

        $activity->entities()->attach($entity);
        $activity->entityStates()->attach([$entityState->id => ['direction' => 'out']]);

        return $activity;
    }

    private function createActivityRelationships()
    {
        $this->rows
            ->filter(function (RowTracker $row) {
                return $row->relatedActivityName !== "";
            })
            ->each(function (RowTracker $row) {
                $activity = $this->activityTracker->getActivity($row->activityAttributesHash);
                // Hook up all the activities who have an entityId === $entityId and that have a name === $row->relatedActivityName
                // by loop through each of these activities and doing the following: (entity state is the entity state from the
                // $activity for the given $entity
                $entity = $activity->entities()->where('name', $row->entityName)->first();
                $entityActivities = $entity->activities()->where('name', $row->relatedActivityName)->get();
                $entityActivities->each(function ($ea) use ($entity, $activity) {
                    $entityState = $ea->entityStates()->where('entity_id', $entity->id)->where('direction',
                        'out')->first();
                    $activity->entityStates()->attach($entityState, ['direction' => 'in']);
                });
            });
    }
}
