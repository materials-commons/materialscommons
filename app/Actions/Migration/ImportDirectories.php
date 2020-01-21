<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportDirectories extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $knownItems;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "files", $ignoreExisting);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping('project2datadir.json', 'datadir_id', 'project_id');
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->knownItems);
        if ($modelData == null) {
            return null;
        }

        $nameWithPath = $modelData['name'];
        $modelData['name'] = basename($nameWithPath);
        $modelData['path'] = $this->removeProjectFromPathName($nameWithPath);
        $modelData['mime_type'] = 'directory';
        $modelData['media_type_description'] = 'directory';
        $modelData['disk'] = 'local';

        return File::create($modelData);
    }

    private function removeProjectFromPathName($nameWithPath)
    {
        // return everything from first '/' to end of string
        return substr($nameWithPath, strpos($nameWithPath, '/'));
    }

    protected function cleanup()
    {
        $this->knownItems = [];
    }

}