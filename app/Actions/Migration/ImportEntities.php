<?php

namespace App\Actions\Migration;

use App\Models\Entity;
use App\Models\File;

class ImportEntities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $entity2project = [];
    private $entity2experiments = [];
    private $entity2files = [];

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "entities", $ignoreExisting);
    }

    protected function setup()
    {
        $this->entity2project = $this->loadItemMapping("project2sample.json", "sample_id", "project_id");
        $this->entity2experiments = $this->loadItemMappingMultiple("experiment2sample.json", "sample_id",
            "experiment_id");
        $this->entity2files = $this->loadItemMappingMultiple("sample2datafile.json", "sample_id", "datafile_id");
    }

    protected function cleanup()
    {
        $this->entity2project = [];
        $this->entity2experiments = [];
        $this->entity2files = [];
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->entity2project);

        if ($modelData == null) {
            return null;
        }

        return Entity::create($modelData);
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return true;
    }

    protected function getModelClass()
    {
        return Entity::class;
    }

    /**
     * @param  \App\Models\Entity  $e
     */
    protected function loadRelationships($e)
    {
        $e->experiments()->syncWithoutDetaching($this->getEntityExperiments($e->uuid));
        $e->files()->syncWithoutDetaching($this->getEntityFiles($e->uuid));
    }

    private function getEntityExperiments($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->entity2experiments, $uuid, function ($experimentUuid) {
            $e = ItemCache::findExperiment($experimentUuid);
            return $e->id ?? null;
        });
    }

    private function getEntityFiles($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->entity2files, $uuid, function ($fileUuid) {
            $f = File::findByUuid($fileUuid);
            return $f->id ?? null;
        });
    }
}