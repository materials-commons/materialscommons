<?php

namespace App\Actions\Migration;

class ImportEntityStateAttributeValues extends AbstractImporter
{
    use ItemLoader;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
        // TODO: Implement setup() method.
    }

    protected function cleanup()
    {
        // TODO: Implement cleanup() method.
    }

    protected function loadData($data)
    {
        // TODO: Implement loadData() method.
    }
}