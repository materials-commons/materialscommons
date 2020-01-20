<?php

namespace App\Actions\Migration;

use App\Models\Attribute;
use App\Models\EntityState;

class ImportEntityStateAttributes extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $property2propertyset;
    private $propertyset2sample;
    private $sample2project;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "attributes", $ignoreExisting);
    }

    protected function setup()
    {
        $this->property2propertyset = $this->loadItemMapping('propertyset2property.json', 'property_id',
            'property_set_id');
        $this->propertyset2sample = $this->loadItemMapping('sample2propertyset.json', 'property_set_id', 'sample_id');
        $this->sample2project = $this->loadItemMapping('project2sample.json', 'sample_id', 'project_id');
    }

    protected function cleanup()
    {
        $this->property2propertyset = [];
        $this->propertyset2sample = [];
        $this->sample2project = [];
    }

    protected function loadData($data)
    {
        $propertyUuid = $data['id'];
        if (!isset($this->property2propertyset[$propertyUuid])) {
            return null;
        }

        $propertySetUuid = $this->property2propertyset[$propertyUuid];
        if (!isset($this->propertyset2sample[$propertySetUuid])) {
            return null;
        }

        $sampleUuid = $this->propertyset2sample[$propertySetUuid];
        if (!isset($this->sample2project[$sampleUuid])) {
            return null;
        }

        $entityState = EntityState::where('uuid', $propertySetUuid)->first();
        if ($entityState == null) {
            return null;
        }

        $modelData = $this->createCommonModelData($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['attributable_type'] = EntityState::class;
        $modelData['attributable_id'] = $entityState->id;
        return Attribute::create($modelData);
    }
}