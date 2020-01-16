<?php

namespace App\Actions\Migration;

use App\Models\Entity;

class ImportEntities extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $knownItems;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "entities", $ignoreExisting);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping("project2sample.json", "sample_id", "project_id");
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->knownItems);

        if ($modelData == null) {
            return null;
        }

        echo "Adding Sample {$modelData['name']}\n";

        return Entity::create($modelData);
    }

    protected function cleanup()
    {
        $this->knownItems = [];
    }
}