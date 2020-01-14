<?php

namespace App\Actions\Migration;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;

class ImportExperiments extends AbstractImporter
{
    use ItemLoader;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
        $this->setupItemMapping("project2process.json", "process_id", "project_id");
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['status'] = $this->mapExperimentStatus($data);

        echo "Adding experiment {$modelData['name']}\n";

        return Experiment::create($modelData);
    }

    private function mapExperimentStatus($data)
    {
        if (!isset($data['status'])) {
            return ExperimentStatus::InProgress;
        }

        switch ($data['status']) {
            case 'done':
                return ExperimentStatus::Done;
            case 'on-hold':
                return ExperimentStatus::OnHold;
            default:
                return ExperimentStatus::InProgress;
        }
    }

    protected function cleanup()
    {
        $this->cleanupItemMapping();
    }
}