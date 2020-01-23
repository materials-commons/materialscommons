<?php

namespace App\Actions\Migration;

use App\Models\File;

class ImportFiles extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $datafile2project;
    private $datafile2datadir;
    private $datafile2experiments;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "files", $ignoreExisting);
    }

    protected function setup()
    {
        $this->datafile2project = $this->loadItemMapping('project2datafile.json', 'datafile_id', 'project_id');
        $this->datafile2datadir = $this->loadItemMapping('datadir2datafile.json', 'datafile_id', 'datadir_id');
        $this->datafile2experiments = $this->loadItemMappingMultiple("experiment2datafile.json", "datafile_id",
            "experiment_id");
    }

    protected function cleanup()
    {
        $this->datafile2project = [];
        $this->datafile2datadir = [];
        $this->datafile2experiments = [];
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->datafile2project);
        if ($modelData == null) {
            return null;
        }

        if (!isset($this->datafile2datadir[$data['id']])) {
            return null;
        }

        $dir = File::where('uuid', $this->datafile2datadir[$data['id']])->first();
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

        return File::create($modelData);
    }

    protected function getModelClass()
    {
        return File::class;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    /**
     * @param  \App\Models\File  $file
     */
    protected function loadRelationships($file)
    {
        $file->experiments()->syncWithoutDetaching($this->getFileExperiments($file->uuid));
    }

    private function getFileExperiments($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->datafile2experiments, $uuid, function ($experimentUuid) {
            $e = ItemCache::findExperiment($experimentUuid);
            return $e->id ?? null;
        });
    }
}