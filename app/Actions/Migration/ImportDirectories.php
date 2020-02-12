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
        $name = basename($nameWithPath);
        if (strpos($nameWithPath, '/') === false) {
            // No other slash so this is the root
            $modelData['name'] = '/';
        } else {
            $modelData['name'] = $name;
        }
        $modelData['path'] = $this->removeProjectFromPathName($nameWithPath);;
        $modelData['mime_type'] = 'directory';
        $modelData['media_type_description'] = 'directory';
        $modelData['disk'] = 'mcfs';

        return File::create($modelData);
    }

    private function removeProjectFromPathName($nameWithPath)
    {
        // return everything from first '/' to end of string
        $name = substr($nameWithPath, strpos($nameWithPath, '/'));
        if (strpos($name, '/') === false) {
            // if no other / then this is root
            return '/';
        }

        return $name;
    }

    protected function cleanup()
    {
        $this->knownItems = [];
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    protected function getModelClass()
    {
        return File::class;
    }

    protected function loadRelationships($item)
    {
    }
}
