<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportFiles extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $knownItems;
    private $knownItems2;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "files", $ignoreExisting);
    }

    protected function setup()
    {
        $this->knownItems = $this->loadItemMapping('project2datafile.json', 'datafile_id', 'project_id');
        $this->knownItems2 = $this->loadItemMapping('datadir2datafile.json', 'datafile_id', 'datadir_id');
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->knownItems);
        if ($modelData == null) {
            return null;
        }

        if (!isset($this->knownItems2[$data['id']])) {
            return null;
        }

        $dir = File::where('uuid', $this->knownItems2[$data['id']])->first();
        if ($dir == null) {
            return null;
        }

        $modelData['current'] = $data['current'];
        $modelData['mime_type'] = $data['mediatype']['mime'];
        $modelData['media_type_description'] = $data['mediatype']['description'];
        $modelData['size'] = $data['size'];
        $modelData['current'] = $data['current'];
        $modelData['uses_uuid'] = $data['usesid'];
        $modelData['checksum'] = $data['checksum'];
        $modelData['directory_id'] = $dir->id;

        echo "Loading file ${modelData['name']}\n";

        return File::create($modelData);
    }

    protected function cleanup()
    {
        $this->knownItems = [];
        $this->knownItems2 = [];
    }
}