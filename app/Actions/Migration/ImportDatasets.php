<?php

namespace App\Actions\Migration;

use App\Models\Dataset;
use App\Models\File;
use Illuminate\Support\Carbon;

class ImportDatasets extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $dataset2project;
    private $fileSelections;
    private $dataset2experiments;
    private $dataset2activities;
    private $dataset2entities;
    private $dataset2files;

    public function __construct($pathToDumpfiles, $ignoreExisting = false)
    {
        parent::__construct($pathToDumpfiles, "datasets", $ignoreExisting);
    }

    protected function setup()
    {
        $this->dataset2project = $this->loadItemMapping("project2dataset.json", "dataset_id", "project_id");
        $this->fileSelections = $this->loadItemToObjectMapping("fileselection.json", "id");
        $this->dataset2experiments = $this->loadItemMappingMultiple("experiment2dataset.json", "dataset_id",
            "experiment_id");
        $this->dataset2activities = $this->loadItemMappingMultiple("dataset2process.json", "dataset_id", "process_id");
        $this->dataset2entities = $this->loadItemMappingMultiple("dataset2sample.json", "dataset_id", "sample_id");
        $this->dataset2files = $this->loadItemMappingMultiple("dataset2datafile.json", "dataset_id", "datafile_id");
    }

    protected function cleanup()
    {
        $this->dataset2project = [];
        $this->fileSelections = [];
        $this->dataset2experiments = [];
        $this->dataset2entities = [];
        $this->dataset2activities = [];
        $this->dataset2files = [];
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->dataset2project);
        if ($modelData == null) {
            return null;
        }
        $modelData['license'] = $data['license']['name'];
        $modelData['license_link'] = $data['license']['link'];
        $modelData['description'] = "{$modelData['description']} {$data['funding']} {$data['institution']}";
        if (isset($data['published'])) {
            $modelData['published_at'] = Carbon::createFromTimestamp($data['birthtime']['epoch_time']);
        }
        if (isset($data['is_privately_published'])) {
            if ($data['is_privately_published']) {
                $modelData['privately_published_at'] = Carbon::createFromTimestamp($data['birthtime']['epoch_time']);
            }
        }
        $modelData['doi'] = $data['doi'];
        $modelData['authors'] = $this->createAuthors($data['authors']);
        $modelData['file_selection'] = $this->createFileSelection($data);

        $ds = Dataset::create($modelData);

        if (isset($data['keywords'])) {
            $ds->attachTags($data['keywords']);
        }

        return $ds;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return true;
    }

    protected function getModelClass()
    {
        return Dataset::class;
    }

    /**
     * @param  \App\Models\Dataset  $ds
     */
    protected function loadRelationships($ds)
    {
        $ds->activities()->syncWithoutDetaching($this->getDatasetActivities($ds->uuid));
        $ds->entities()->syncWithoutDetaching($this->getDatasetEntities($ds->uuid));
        $ds->experiments()->syncWithoutDetaching($this->getDatasetExperiments($ds->uuid));
        $ds->files()->syncWithoutDetaching($this->getDatasetFiles($ds->uuid));
    }

    private function createAuthors($authors)
    {
        if (!is_array($authors)) {
            return "";
        }

        $authorsList = [];
        foreach ($authors as $author) {
            $entry = $author['firstname']." ".$author['lastname']." (".$author['affiliation'].")";
            array_push($authorsList, $entry);
        }

        return implode("; ", $authorsList);
    }

    private function createFileSelection($data)
    {
        if (isset($data['file_selection'])) {
            return $this->createFromFileSelection($data['file_selection']);
        }

        if (isset($data['selection_id'])) {
            return $this->createFromFileSelection($this->fileSelections[$data['selection_id']]);
        }

        return null;
    }

    private function createFromFileSelection($fs)
    {
        $dsfs = [];
        $dsfs['include_files'] = $this->convertFSEntries($fs['include_files']);
        $dsfs['exclude_files'] = $this->convertFSEntries($fs['exclude_files']);
        $dsfs['include_dirs'] = $this->convertFSEntries($fs['include_dirs']);
        $dsfs['exclude_dirs'] = $this->convertFSEntries($fs['exclude_dirs']);
        return $dsfs;
    }

    private function convertFSEntries($entries)
    {
        $convertedEntries = [];
        foreach ($entries as $entry) {
            $entry = substr($entry, strpos($entry, '/'));
            array_push($convertedEntries, $entry);
        }

        return $convertedEntries;
    }

    private function getDatasetActivities($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->dataset2activities, $uuid, function ($activityUuid) {
            $a = ItemCache::findActivity($activityUuid);
            return $a->id ?? null;
        });
    }

    private function getDatasetEntities($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->dataset2entities, $uuid, function ($entityUuid) {
            $e = ItemCache::findEntity($entityUuid);
            return $e->id ?? null;
        });
    }

    private function getDatasetExperiments($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->dataset2experiments, $uuid, function ($experimentUuid) {
            $e = ItemCache::findExperiment($experimentUuid);
            return $e->id ?? null;
        });
    }

    private function getDatasetFiles($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->dataset2files, $uuid, function ($fileUuid) {
            $f = File::findByUuid($fileUuid);
            return $f->id ?? null;
        });
    }
}