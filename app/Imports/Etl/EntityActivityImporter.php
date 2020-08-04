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
use App\Models\Experiment;
use App\Models\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EntityActivityImporter
{
    const GLOBAL_WORKSHEET_NAME = 'mc constants';

    private $projectId;
    private $experimentId;

    /** @var \App\Models\Experiment */
    private $experiment;
    private $userId;

    private $sawHeader = false;
    private $headerTracker;
    private $worksheet;
    private $entityTracker;
    private $activityTracker;
    private $rowNumber;
    private $rows;
    private $currentSheetRows;
    private $getFileByPathAction;
    private $globalSettings;
    private $currentSheetPosition;
    private $etlState;

    public function __construct($projectId, $experimentId, $userId, EtlState $etlState)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->rowNumber = 0;
        $this->rows = collect();
        $this->currentSheetRows = collect();
        $this->currentSheetPosition = 1;

        $this->entityTracker = new EntityTracker();
        $this->activityTracker = new HashedActivityTracker();
        $this->getFileByPathAction = new GetFileByPathAction();
        $this->globalSettings = new GlobalSettingsLoader();
        $this->etlState = $etlState;
    }

    public function execute($spreadsheetPath)
    {
        $this->experiment = Experiment::findOrFail($this->experimentId);
        $spreadsheet = $this->loadSpreadsheet($spreadsheetPath);
        $this->loadGlobalSettingsIfExists($spreadsheet);
        $this->processWorksheets($spreadsheet);
        $this->afterImport();
    }

    private function loadSpreadsheet($path)
    {
        $reader = new Xlsx();
        $reader->setReadEmptyCells(true);
        $reader->setReadDataOnly(false);
        return $reader->load($path);
    }

    private function loadGlobalSettingsIfExists(Spreadsheet $spreadsheet)
    {
        $worksheet = $spreadsheet->getSheetByName(self::GLOBAL_WORKSHEET_NAME);
        if (is_null($worksheet)) {
            return;
        }
        $this->globalSettings->loadGlobalWorksheet($worksheet);
    }

    private function processWorksheets(Spreadsheet $spreadsheet)
    {
        $this->currentSheetPosition = 1;
        $worksheets = $spreadsheet->getAllSheets();
        $this->etlState->etlRun->n_sheets = sizeof($worksheets);
        foreach ($worksheets as $worksheet) {
            if ($worksheet->getTitle() == self::GLOBAL_WORKSHEET_NAME) {
                // Skip processing the global worksheet as the sheet has already been processed
                // and is used to hold settings that apply across sheets. A "normal" worksheet
                // contains samples for the processing step, while the global worksheet contains
                // settings to apply with processing steps.
                continue;
            }
            $this->processWorksheet($worksheet);
            $this->etlState->etlRun->n_sheets_processed++;
            $this->currentSheetPosition++;
        }
    }

    private function processWorksheet(Worksheet $worksheet)
    {
        $blankRowCount = 0;
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
        $this->etlState->done();
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
        $this->etlState->etlRun->n_entities++;

        /** @var EntityState $state */
        $state = $entity->entityStates()->first();
        $this->addAttributesToEntity($row->entityAttributes, $entity, $state, $row);
        // Add a new activity
        $activity = $this->addNewActivity($entity, $state, $row);
        $this->activityTracker->addActivity($row->activityAttributesHash, $activity);
        $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $activity);
    }

    private function addAttributesToEntity(Collection $entityAttributes, Entity $entity, EntityState $state,
        RowTracker $rowTracker)
    {
        $seenAttributes = collect();
        $attributePosition = 1;
        $entityAttributes->each(function ($attr) use ($state, $entity, $seenAttributes, &$attributePosition) {
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
                    'eindex'            => $attributePosition,
                    'attributable_type' => EntityState::class,
                ]);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $attr->unit,
                    'val'          => ['value' => $attr->value],
                ]);
                $seenAttributes->put($attr->name, $a);
                $attributePosition++;
            }
        });
        $globalAttributes = $this->globalSettings->getGlobalSettingsForWorksheet($rowTracker->activityName);
        foreach ($globalAttributes as $globalAttribute) {
            if ($globalAttribute->attributeHeader->attrType === "entity") {
                $a = Attribute::create([
                    'name'              => $globalAttribute->attributeHeader->name,
                    'attributable_id'   => $state->id,
                    'eindex'            => $attributePosition,
                    'attributable_type' => EntityState::class,
                ]);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $globalAttribute->attributeHeader->unit,
                    'val'          => ['value' => $globalAttribute->value],
                ]);
                $attributePosition++;
            }
        }
    }

    private function addFilesToActivityAndEntity(Collection $fileAttributes, ?Entity $entity, ?Activity $activity)
    {
        $fileAttributes->each(function (ColumnAttribute $attr) use ($entity, $activity) {
            $header = $this->headerTracker->getHeaderByIndex($attr->columnNumber - 2);

            // Multiple files can be specified in a cell when they are separated by a semi-colon (;), eg
            // file1.txt;file2.txt
            foreach (explode(";", $attr->value) as $value) {
                $value = trim($value);

                if ($value == "") {
                    continue;
                }

                $path = "{$header->name}/{$value}";
                if (strpos($value, "/") !== false) {
                    // Cell contains the path, no need to use the header
                    $path = $value;
                }

                if ($this->isWildCard($path)) {
                    $this->addWildCardFiles($path, $entity, $activity);
                    return;
                }

                if (($dir = $this->isDirectory($path)) !== null) {
                    $this->addDirectoryFiles($dir, $entity, $activity);
                    return;
                }

                $this->addSingleFile($path, $entity, $activity);
            }
        });
    }

    private function isWildCard($path)
    {
        return Str::contains($path, ['*', '?']);
    }

    private function addWildCardFiles($path, ?Entity $entity, ?Activity $activity = null)
    {
        $dirPath = dirname($path);
        $expression = basename($path);
        $dir = File::where('path', $dirPath)
                   ->where('project_id', $this->projectId)
                   ->first();

        if (is_null($dir)) {
            return;
        }

        File::where('directory_id', $dir->id)
            ->where('mime_type', '<>', 'directory')
            ->chunk(100, function ($files) use ($entity, $activity, $expression) {
                $files->each(function (File $file) use ($entity, $activity, $expression) {
                    if (!fnmatch($expression, $file->name)) {
                        return;
                    }

                    $this->addFileToActivityAndEntity($file, $activity, $entity);
                });
            });
    }

    private function isDirectory($path)
    {
        $dir = $this->getFileByPathAction->execute($this->projectId, $path);
        return optional($dir)->mime_type == "directory" ? $dir : null;
    }

    private function addDirectoryFiles(File $dir, ?Entity $entity, ?Activity $activity)
    {
        File::where('directory_id', $dir->id)
            ->where('mime_type', '<>', 'directory')
            ->chunk(100, function ($files) use ($entity, $activity) {
                $files->each(function (File $file) use ($entity, $activity) {
                    $this->addFileToActivityAndEntity($file, $activity, $entity);
                });
            });
    }

    private function addSingleFile($path, ?Entity $entity, ?Activity $activity)
    {
        $file = $this->getFileByPathAction->execute($this->projectId, $path);
        $this->addFileToActivityAndEntity($file, $activity, $entity);
        if (is_null($file)) {
            return;
        }

        $this->experiment->files()->syncWithoutDetaching($file);
    }

    private function addFileToActivityAndEntity(?File $file, ?Activity $activity, ?Entity $entity)
    {
        if (is_null($file)) {
            return;
        }

        $this->addFileToActivity($file, $activity);
        $this->addFileToEntity($file, $entity);
    }

    private function addFileToActivity(?File $file, ?Activity $activity)
    {
        if (is_null($file)) {
            return;
        }

        if (is_null($activity)) {
            return;
        }

        $activity->files()->attach([$file->id => ['direction' => 'in']]);
    }

    private function addFileToEntity(?File $file, ?Entity $entity)
    {
        if (is_null($file)) {
            return;
        }

        if (is_null($entity)) {
            return;
        }

        $entity->files()->syncWithoutDetaching([$file->id]);
    }

    /*
     * if (Str::of($attr->value)->contains(['*', '?'])) {
     *    matchOnRegex();
     *    return;
     * }
     *
     * matchOnFileOrDir();
     */

    private function addToExistingEntity(RowTracker $row)
    {
//        $activity = $this->activityTracker->getActivity($row->activityAttributesHash);
//        if ($activity === null) {
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
        $this->addAttributesToEntity($row->entityAttributes, $entity, $state, $row);
        $activity = $this->addNewActivity($entity, $state, $row);
        $this->activityTracker->addActivity($row->activityAttributesHash, $activity);
        $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $activity);
//        } else {
//            // Matching activity found, so add attribute values as additional values on existing
//            // measurements for this entity. To do this first get the entity state that is associated
//            // with the entity for this activity. Then add the attributes.
//            $entity = $this->entityTracker->getEntity($row->entityName);
//            $entityStates = $activity->entityStates()->get();
//            $entityState = $entityStates->firstWhere('entity_id', $entity->id);
//            if (is_null($entityState)) {
//                return;
//            }
//            $this->addValuesToEntityStateAttributes($row->entityAttributes, $entityState);
//            $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $entityState, $activity);
//        }
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
        $attributePosition = 1;
        $attributes = $rowTracker->activityAttributes->map(function (ColumnAttribute $attr) use (&$attributePosition) {
            return [
                'name'   => $attr->name,
                'value'  => $attr->value,
                'unit'   => $attr->unit,
                'eindex' => $attributePosition++,
            ];
        })->toArray();

        // Add global settings for worksheet (activity)
        $globalAttributes = $this->globalSettings->getGlobalSettingsForWorksheet($rowTracker->activityName);
        foreach ($globalAttributes as $globalAttribute) {
            if ($globalAttribute->attributeHeader->attrType === "activity") {
                array_push($attributes, [
                    'name'   => $globalAttribute->attributeHeader->name,
                    'unit'   => $globalAttribute->attributeHeader->unit,
                    'value'  => $globalAttribute->value,
                    'eindex' => $attributePosition++,
                ]);
            }
        }

        $activity = $createActivityAction([
            'name'          => $rowTracker->activityName,
            'project_id'    => $this->projectId,
            'experiment_id' => $this->experimentId,
            'attributes'    => $attributes,
            'eindex'        => $this->currentSheetPosition,
        ], $this->userId);

        $activity->entities()->attach($entity);
        $activity->entityStates()->attach([$entityState->id => ['direction' => 'out']]);
        $this->etlState->etlRun->n_activities++;

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
                    $entityState = $ea->entityStates()->where('entity_id', $entity->id)
                                      ->where('direction', 'out')
                                      ->first();
                    $activity->entityStates()->attach($entityState, ['direction' => 'in']);
                });
            });
    }
}
