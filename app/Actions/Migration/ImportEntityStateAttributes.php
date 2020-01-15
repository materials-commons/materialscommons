<?php

namespace App\Actions\Migration;

class ImportEntityStateAttributes extends AbstractImporter
{
    use ItemLoader;

    private $knownItems;
    private $knownItems2;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping('propertyset2property.json', 'property_id', 'property_set_id');
        $this->knownItems2 = $this->loadItemMapping('sample2propertyset.json', 'property_set_id', 'sample_id');
    }

    protected function cleanup()
    {
        $this->knownItems = [];
        $this->knownItems2 = [];
    }

    protected function loadData($data)
    {
        $propertyUuid = $data['id'];
        if (!isset($this->knownItems[$propertyUuid])) {
            return null;
        }

        $propertySetUuid = $this->knownItems[$propertyUuid];
        if (!isset($this->knownItems2[$propertySetUuid])) {
            return null;
        }

        $sampleUuid = $this->knownItems2[$propertySetUuid];
    }
}