<?php

namespace App\Actions\Migration;

use App\Models\Activity;

class ImportActivities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $knownItems;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "activities", $ignoreExisting);
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

        echo "Adding Process {$modelData['name']}\n";

        return Activity::create($modelData);
    }

    protected function cleanup()
    {
        $this->knownItems = [];
    }
}