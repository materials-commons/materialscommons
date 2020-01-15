<?php

namespace App\Actions\Migration;

use App\Models\Entity;
use App\Models\EntityState;

class ImportEntityStates extends AbstractImporter
{
    use ItemLoader;

    private $knownItems;
    private $knownItems2;

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
        $modelData['name'] = 'Imported Dataset';
        $modelData['uuid'] = $propertySetUuid;

        $entity = Entity::where('uuid', $sampleUuid)->first();
        if ($entity == null) {
            return null;
        }

        $modelData['entity_id'] = $entity->id;
        $modelData['owner_id'] = $entity->owner_id;

        return EntityState::create($modelData);
    }
}