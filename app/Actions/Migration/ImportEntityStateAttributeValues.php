<?php

namespace App\Actions\Migration;

class ImportEntityStateAttributeValues extends AbstractImporter
{
    use ItemLoader;

    private $measurement2attribute;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
        $this->measurement2attribute = $this->loadItemMapping('property2measurement.json', 'measurement_id',
            'property_id');
    }

    protected function cleanup()
    {
        $this->measurement2attribute = [];
    }

    protected function loadData($data)
    {
        // TODO: Implement loadData() method.
    }
}