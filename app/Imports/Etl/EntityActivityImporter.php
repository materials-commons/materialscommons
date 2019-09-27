<?php

namespace App\Imports\Etl;

use App\Actions\Entities\CreateEntityAction;
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

    public function __construct($projectId, $experimentId, $userId)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;

        $this->entityTracker = new EntityTracker();
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

        $index = 0;
        $cellIterator = $row->getDelegate()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach ($cellIterator as $cell) {
            $value = $cell->getValue();
            if ($value === null) {
                continue;
            }
            $value = trim($value);
            if ($index === 0) {
                if (!$this->entityTracker->hasEntity($value)) {
                    $entity = $createEntityAction([
                        'name'          => $value,
                        'project_id'    => $this->projectId,
                        'experiment_id' => $this->experimentId,
                    ], $this->userId);
                    $this->entityTracker->addEntity($entity);
                }
            } elseif ($index === 1) {
                // related activity
            } else {
                // attributes
            }
        }
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
            },
        ];
    }
}
