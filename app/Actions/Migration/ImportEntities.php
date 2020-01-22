<?php

namespace App\Actions\Migration;

use App\Models\Entity;

class ImportEntities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $sample2project = [];
    private $sample2experiments = [];

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "entities", $ignoreExisting);
    }

    protected function setup()
    {
        $this->sample2project = $this->loadItemMapping("project2sample.json", "sample_id", "project_id");
        $this->sample2experiments = $this->loadItemMappingMultiple("experiment2sample.json", "sample_id",
            "experiment_id");
    }

    protected function cleanup()
    {
        $this->sample2project = [];
        $this->sample2experiments = [];
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->sample2project);

        if ($modelData == null) {
            return null;
        }

        $e = Entity::create($modelData);

        $experiments = $this->getEntityExperiments($e->uuid);
        $e->experiments()->syncWithoutDetaching($experiments);

        return $e;
    }

    private function getEntityExperiments($uuid)
    {
        ItemCache::loadItemsFromMultiple($this->sample2experiments, $uuid, function ($experimentUuid) {
            $e = ItemCache::findExperiment($experimentUuid);
            return $e->id ?? null;
        });
    }
}