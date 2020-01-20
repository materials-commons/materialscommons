<?php

namespace App\Actions\Migration;

use App\Models\Dataset;
use Illuminate\Support\Carbon;

class ImportDatasets extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $dataset2project;
    private $fileSelections;

    public function __construct($pathToDumpfiles, $ignoreExisting = false)
    {
        parent::__construct($pathToDumpfiles, "datasets", $ignoreExisting);
    }

    protected function setup()
    {
        $this->dataset2project = $this->loadItemMapping("project2dataset", "dataset_id", "project_id");
        $this->fileSelections = $this->loadItemToObjectMapping("fileselection.json", "id");
    }

    protected function cleanup()
    {
        $this->dataset2project = [];
        $this->fileSelections = [];
    }

    protected function loadData($data)
    {
        $modelData = $this->createModelDataForKnownItems($data, $this->dataset2project);
        $modelData['license'] = $data['license']['name'];
        $modelData['license_link'] = $data['license']['link'];
        $modelData['description'] = "{$modelData['description']} {$data['funding']} {$data['institution']}";
        if ($modelData['published']) {
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

        return Dataset::create($modelData);
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
}