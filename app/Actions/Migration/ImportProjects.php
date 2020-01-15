<?php

namespace App\Actions\Migration;

use App\Models\Project;

class ImportProjects extends AbstractImporter
{
    use ItemCreater;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
    }

    protected function loadData($data)
    {
        $modelData = $this->createCommonModelData($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['is_active'] = true;
        echo "Adding project {$modelData['name']}\n";

        return Project::create($modelData);
    }

    protected function cleanup()
    {
    }
}