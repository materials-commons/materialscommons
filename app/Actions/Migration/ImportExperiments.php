<?php

namespace App\Actions\Migration;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;

class ImportExperiments extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $experiment2project;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "experiments", $ignoreExisting);
    }

    protected function setup()
    {
        $this->experiment2project = $this->loadItemMapping("project2experiment.json", "experiment_id", "project_id");
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->experiment2project);
        if ($modelData == null) {
            return null;
        }

        $modelData['status'] = $this->mapExperimentStatus($data);

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
        $this->experiment2project = [];
    }
}