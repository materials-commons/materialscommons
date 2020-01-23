<?php

namespace App\Actions\Migration;

use App\Models\Activity;
use App\Models\File;

class ImportActivities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $process2project;
    private $process2entities;
    private $process2experiments;
    private $process2files;

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
        $this->process2files = $this->loadItemMappingMultiple("process2file.json", "process_id", "datafile_id");
    }

    protected function cleanup()
    {
        $this->process2project = [];
        $this->process2entities = [];
        $this->process2experiments = [];
        $this->process2files = [];
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return true;
    }

    protected function getModelClass()
    {
        return Activity::class;
    }

    /**
     * @param  \App\Models\Activity  $activity
     */
    protected function loadRelationships($activity)
    {
        $activity->entities()->syncWithoutDetaching($this->getActivityEntities($activity->uuid));
        $activity->entityStates()->syncWithoutDetaching($this->getActivityEntityStates($activity->uuid));
        $activity->experiments()->syncWithoutDetaching($this->getActivityExperiments($activity->uuid));
        $activity->files()->syncWithoutDetaching($this->getActivityFiles($activity->uuid));
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->process2project);

        if ($modelData == null) {
            return null;
        }

        return Activity::create($modelData);
    }


    private function getActivityEntities($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2entities, $uuid, function ($entry) {
            $e = ItemCache::findEntity($entry["sample_id"]);
            return $e->id ?? null;
        });
    }

    private function getActivityEntityStates($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2entities, $uuid, function ($entry) {
            $es = ItemCache::findEntityState($entry["property_set_id"]);
            if ($es == null) {
                return null;
            }
            return [$es->id => ['direction' => $entry['direction']]];
        });
    }

    private function getActivityExperiments($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2experiments, $uuid, function ($experimentUuid) {
            $e = ItemCache::findExperiment($experimentUuid);
            return $e->id ?? null;
        });
    }

    private function getActivityFiles($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->process2files, $uuid, function ($fileUuid) {
            $f = File::findByUuid($fileUuid);
            return $f->id ?? null;
        });
    }
}