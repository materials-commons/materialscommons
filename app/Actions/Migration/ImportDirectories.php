<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportDirectories extends AbstractImporter
{
    use ItemLoader;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
        $this->setupItemMapping('project2datadir.json', 'datadir_id', 'project_id');
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);
        if ($modelData == null) {
            return null;
        }

        $nameWithPath = $modelData['name'];
        $modelData['name'] = basename($nameWithPath);
        $modelData['path'] = $this->removeProjectFromPathName($nameWithPath);
        $modelData['mime_type'] = 'directory';
        $modelData['media_type_description'] = 'directory';
        $modelData['disk'] = 'local';

        echo "Adding directory {$modelData['name']}, path: {$modelData['path']}\n";

        return File::create($modelData);
    }

    private function removeProjectFromPathName($nameWithPath)
    {
        // return everything from first '/' to end of string
        return substr($nameWithPath, strpos($nameWithPath, '/'));
    }

    protected function cleanup()
    {
        $this->cleanupItemMapping();
    }

}