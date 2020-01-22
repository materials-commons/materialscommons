<?php

namespace App\Actions\Migration;

use App\Models\Activity;

class ImportActivities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $process2project;
    private $process2entities;
    private $process2experiments;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "activities", $ignoreExisting);
    }

    protected function setup()
    {
        $this->process2project = $this->loadItemMapping("project2process.json", "process_id", "project_id");
        $this->process2entities = $this->loadItemToObjectMappingMultiple("process2sample.json", "process_id");
        $this->process2experiments = $this->loadItemMappingMultiple("experiment2process.json", "process_id",
            "experiment_id");
    }

    protected function cleanup()
    {
        $this->process2project = [];
        $this->process2entities = [];
        $this->process2experiments = [];
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->process2project);

        if ($modelData == null) {
            return null;
        }

        $activity = Activity::create($modelData);

        $activity->entities()->syncWithoutDetaching($this->getActivityEntities($activity->uuid));
        $activity->entityStates()->syncWithoutDetaching($this->getActivityEntityStates($activity->uuid));
        $activity->experiments()->syncWithoutDetaching($this->getActivityExperiments($activity->uuid));

        return $activity;
    }


    private function getActivityEntities($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2entities, $uuid, function ($entry) {
            return ItemCache::findEntity($entry["sample_id"]);
        });
    }

    private function getActivityEntityStates($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2entities, $uuid, function ($entry) {
            return ItemCache::findEntityState($entry["property_set_id"]);
        });
    }

    private function getActivityExperiments($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2experiments, $uuid, function ($experimentUuid) {
            return ItemCache::findExperiment($experimentUuid);
        });
    }
}