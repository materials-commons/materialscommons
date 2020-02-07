<?php

namespace App\Actions\Migration;

use App\Models\EntityState;

class ImportEntityStates extends AbstractImporter
{
    use ItemLoader;

    private $knownItems;
    private $knownItems2;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "entity_states", $ignoreExisting);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping('project2sample.json', 'sample_id', 'project_id');
        $this->knownItems2 = $this->loadItemMapping('sample2propertyset.json', 'property_set_id', 'sample_id');
    }

    protected function cleanup()
    {
        $this->knownItems = [];
        $this->knownItems2 = [];
    }

    protected function loadData($data)
    {
        if (!isset($this->knownItems2[$data['id']])) {
            return null;
        }

        $propertySetUuid = $data['id'];
        $sampleUuid = $this->knownItems2[$propertySetUuid];

        if (!isset($this->knownItems[$sampleUuid])) {
            return null;
        }

        $modelData = [];
        $modelData['name'] = 'Imported Entity State';
        $modelData['uuid'] = $propertySetUuid;

        $entity = ItemCache::findEntity($sampleUuid);
        if ($entity == null) {
            return null;
        }

        $modelData['entity_id'] = $entity->id;
        $modelData['owner_id'] = $entity->owner_id;

        return EntityState::create($modelData);
    }

    protected function getModelClass()
    {
        return EntityState::class;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    protected function loadRelationships($item)
    {
    }
}