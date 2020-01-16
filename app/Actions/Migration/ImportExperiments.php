<?php

namespace App\Actions\Migration;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;

class ImportExperiments extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $knownItems;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "experiments", $ignoreExisting);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping("project2process.json", "process_id", "project_id");
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->knownItems);
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
        $this->knownItems = [];
    }
}