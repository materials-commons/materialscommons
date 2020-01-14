<?php

namespace App\Actions\Migration;

use App\Models\Entity;

class ImportEntities extends AbstractImporter
{
    use ItemLoader;

    protected function setup()
    {
        $this->setupItemMapping("project2sample.json", "sample_id", "project_id");
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);

        if ($modelData == null) {
            return null;
        }

        echo "Adding Sample {$modelData['name']}\n";

        return Entity::create($modelData);
    }

    protected function cleanup()
    {
        $this->cleanupItemMapping();
    }
}