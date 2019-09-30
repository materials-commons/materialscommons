<?php

namespace App\Imports\Etl;

use App\Actions\Activities\CreateActivityAction;
use App\Actions\Entities\CreateEntityAction;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Row;

class EntityActivityImporter implements OnEachRow, WithEvents
{
    private $projectId;
    private $experimentId;
    private $userId;

    private $sawHeader = false;
    private $headerTracker;
    public $worksheet;
    private $entityTracker;
    private $activityTracker;
    private $rowNumber;
    private $rows;

    public function __construct($projectId, $experimentId, $userId)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->rowNumber = 0;
        $this->rows = collect();

        $this->entityTracker = new EntityTracker();
        $this->activityTracker = new HashedActivityTracker();
    }

    public function onRow(Row $row)
    {
        if (!$this->sawHeader) {
            $this->processHeader($row);
        } else {
            $this->processSample($row);
        }
    }

    public function getHeaders()
    {
        return $this->headerTracker;
    }

    private function processHeader(Row $row)
    {
        $index = 0;
        $cellIterator = $row->getDelegate()->getCellIterator();
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
        $createEntityAction = new CreateEntityAction();
        $rowTracker = new RowTracker($this->rowNumber, $this->worksheet->getTitle());
        $rowTracker->loadRow($row, $this->headerTracker);
        $this->rows->push($rowTracker);
        $this->rows->each(function(RowTracker $val) use ($createEntityAction) {
            if (!$this->entityTracker->hasEntity($val->entityName)) {
                $entity = $createEntityAction([
                    'name'          => $val->entityName,
                    'project_id'    => $this->projectId,
                    'experiment_id' => $this->experimentId,
                ], $this->userId);
                $this->entityTracker->addEntity($entity);
                $state = $entity->entityStates()->first();
                $seenAttributes = collect();
                $val->entityAttributes->each(function($attr) use ($state, $seenAttributes, $entity) {
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

                $activity = $this->activityTracker->getActivity($val->activityAttributesHash);
                if ($activity === null) {
                    // Add a new activity
                    $activity = $this->addNewActivity($entity, $state, $val);
                    $this->activityTracker->addActivity($val->activityAttributesHash, $activity);
                } else {
                    // What needs to be done here?
                    // $activity->entities()->attach($entity);
                    // $activity->entityStates()->attach($entity);
                }
            }
        });
    }

    private function addNewActivity(Entity $entity, EntityState $entityState, RowTracker $rowTracker)
    {
        $createActivityAction = new CreateActivityAction();
        $attributes = $rowTracker->activityAttributes->map(function(ColumnAttribute $attr) {
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
        $activity->entityStates()->attach($entityState);
        return $activity;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                // For each worksheet reset some of the state
                $this->sawHeader = false;
                $this->headerTracker = new HeaderTracker();
                $this->worksheet = $event->getDelegate()->getDelegate();
                $this->rowNumber = 0;
            },
        ];
    }
}
