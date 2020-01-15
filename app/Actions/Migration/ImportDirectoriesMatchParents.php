<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportDirectoriesMatchParents extends AbstractImporter
{
    use ItemLoader;

    private $knownItems;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
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

        $parentDir = File::where('uuid', $data['parent'])->first();
        if ($parentDir == null) {
            return null;
        }

        echo "Updating parent link for {$dir->path} to point at {$parentDir->path}\n";

        $dir->update(['directory_id' => $parentDir->id]);
        return $dir;
    }

    protected function cleanup()
    {
        $this->knownItems = [];
    }
}