<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportDirectoriesMatchParents extends AbstractImporter
{
    use ItemLoader;

    private $knownItems;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        // Always ignore existing because we are working with files that were just loaded in the previous step
        parent::__construct($pathToDumpfiles, "files", false);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping('project2datadir.json', 'datadir_id', 'project_id');
    }

    protected function loadData($data)
    {
        if (!isset($this->knownItems[$data['id']])) {
            return null;
        }

        if (!isset($data['parent'])) {
            return null;
        }

        if ($data['parent'] == '') {
            return null;
        }

        if (!isset($this->knownItems[$data['parent']])) {
            return null;
        }

        $dir = File::where('uuid', $data['id'])->first();
        if ($dir == null) {
            return null;
        }

        if (!is_null($dir->directory_id)) {
            return null;
        }

        $parentDir = File::where('uuid', $data['parent'])->first();
        if ($parentDir == null) {
            return null;
        }

        $dir->update(['directory_id' => $parentDir->id]);
        return $dir;
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