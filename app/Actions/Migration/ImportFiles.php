<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportFiles extends AbstractImporter
{
    use ItemLoader;

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
        $this->setupItemMapping('project2datafile.json', 'datafile_id', 'project_id');
        $this->setupItemMapping('datadir2datafile.json', 'datafile_id', 'datadir_id', true);
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);
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
        $this->cleanupItemMapping();
    }
}