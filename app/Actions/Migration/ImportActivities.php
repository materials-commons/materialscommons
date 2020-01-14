<?php

namespace App\Actions\Migration;

use App\Models\Activity;

class ImportActivities extends AbstractImporter
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

        echo "Adding Process {$modelData['name']}\n";

        return Activity::create($modelData);
    }

    protected function cleanup()
    {
        $this->cleanupItemMapping();
    }
}