<?php

namespace App\Actions\Migration;

use App\Models\Activity;

class ImportActivities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $process2project;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "activities", $ignoreExisting);
    }

    protected function setup()
    {
        $this->process2project = $this->loadItemMapping("project2process.json", "process_id", "project_id");
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->process2project);

        if ($modelData == null) {
            return null;
        }

//        echo "Adding Process {$modelData['name']}\n";

        return Activity::create($modelData);
    }

    protected function cleanup()
    {
        $this->process2project = [];
    }
}