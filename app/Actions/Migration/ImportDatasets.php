<?php

namespace App\Actions\Migration;

use App\Actions\Datasets\UpdateDatasetWorkflowSelectionAction;
use App\Actions\Workflows\CreateWorkflowAction;
use App\Models\Dataset;
use App\Models\Download;
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
    private $datafile2project;
    private $entity2project = [];
    private $createWorkflowAction;
    private $updateDatasetWorkflowSelectionAction;

    private $dataset2workflow = [
        "57490e70-df32-4592-8a6f-8a6cfbd36174" => "Polish -> SEM ->EBSD-> EPMA ->Analyze",
        "9464559b-df1a-4e9f-973f-96ebacd7b2e9" => "Heat Treat ->TEM -> precipitate characterization",
        "238117f2-6066-4a78-a6f0-e2afdf664625" => "XRD->SEM ->EBSD->Tensile Testing ->Analyze",
        "a4eb07b1-e774-41b3-bd91-49a4f318a1bb" => "SEM/ STEM/ EDS Characterization  -> Free Immersion? (yes, right)-> SEM/TEM Cross Section\nFree Immersion? (no) -> Anodic Polarization -> SEM and TEM characterization -> Electrochemical Quantification",
        "b8bc8038-a735-4cb9-9a9e-a0fb912b248c" => "Section(right)->Coat Sample?(yes, right)->Coat Sectioned Samples(right)->Oxidation Exposure for different times(right)->SEM/STEM-EDS/TEM\nCoat Sample?(no)->Oxidation Exposure for different times(right)->SEM/STEM-EDS/TEM",
        "80e14bcb-3894-4fff-98e7-9ade02441887" => "EBSD Characterization -> Experimental?(yes, right) ->Tension/Compression Testing -> SEM DIC -> Analysis\nExperimental?(no) ->  2D and 3D CPFE Simulations (right) -> Analysis",
        "fa4845ab-73af-4b30-9a5f-7c184ceb3852" => "Heat Treatment ->Ultrasonic Fatigue in SEM?(yes, right)->Vary Environment ->Analyze\nUltrasonic Fatigue in SEM?(no)->Ultrasonic Fatigue->Analyze",
        "74df8927-89a2-4759-90a5-c787686f78a0" => "Oxidation Exposures for different times -> Hardness Profile -> SEM/TEM/STEM EDS Characterization",
        "0b6dab0e-7e17-4129-9ccf-9e80444b8297" => "Low-Cycle Fatigue + HEDM -> Stress-Strain loop and intensity data for each cycle",
        "ac483ba0-19f4-4528-9601-48635d7833f2" => "Room temperature compression-> Vary Heat Treatment -> EBSD -> GOS analysis",
        "60b34544-47d1-4642-8837-03e1d31ec94d" => "Solution Treatment -> Oxidation Exposure? (yes, right) -> Vary Aging Condition -> WDS\nOxidation Exposure? (no) -> Vary Aging Condition -> WDS",
        "21706c8b-fc7a-4db8-8a24-fed38a197167" => "Vary Alloy -> Annealing Heat Treatment -> Characterize texture and microstructure -> Small Fatigue Crack Growth/Tensile Testing/Ultrasonic Fatigue Lifetime with fractography",
    ];

    public function __construct($pathToDumpfiles, $ignoreExisting = false)
    {
        parent::__construct($pathToDumpfiles, "datasets", $ignoreExisting);
        $this->createWorkflowAction = new CreateWorkflowAction();
        $this->updateDatasetWorkflowSelectionAction = new UpdateDatasetWorkflowSelectionAction();
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
        $this->datafile2project = $this->loadItemMapping('project2datafile.json', 'datafile_id', 'project_id');
        $this->entity2project = $this->loadItemMapping("project2sample.json", "sample_id", "project_id");
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
        $modelData = $this->createCommonModelData($data);
        if ($modelData == null) {
            return null;
        }

        $projectId = $this->findProjectForDataset($modelData['uuid']);
        if ($projectId == null) {
            $isPublished = "no";
            if (isset($data['published'])) {
                if ($data['published']) {
                    $isPublished = "yes";
                }
            }
            echo "Unable to find project for dataset {$data['title']}/{$data['id']} owned by {$data['owner']} Published: {$isPublished}\n";
            echo "::{$data['id']}\n";
            return null;
        }

        $modelData['project_id'] = $projectId;
        $modelData['license'] = $data['license']['name'];
        $modelData['license_link'] = $data['license']['link'];
        $modelData['description'] = "{$modelData['description']} {$data['funding']} {$data['institution']}";

        if (isset($data['published'])) {
            if ($data['published']) {
                $modelData['published_at'] = Carbon::createFromTimestamp($data['birthtime']['epoch_time']);
            }
        } elseif (isset($data['is_privately_published'])) {
            if ($data['is_privately_published']) {
                if ($data['is_privately_published']) {
                    $modelData['privately_published_at'] = Carbon::createFromTimestamp($data['birthtime']['epoch_time']);
                }
            }
        }
        $modelData['doi'] = $data['doi'];
        $modelData['authors'] = $this->createAuthors($data['authors']);
        $modelData['file_selection'] = $this->createFileSelection($data);

        $ds = Dataset::create($modelData);

        if (isset($data['keywords'])) {
            $ds->attachTags($data['keywords']);
        }

        if (isset($data['download_count'])) {
            $this->addDownloads($ds, $data['download_count']);
        }

        if (isset($data['published'])) {
            if ($data['published']) {
                $this->addWorkflow($ds);
            }
        }

        return $ds;
    }

    private function findProjectForDataset($datasetUuid)
    {
        if (isset($this->dataset2project[$datasetUuid])) {
            $projectId = $this->findProjectIdForUuid($this->dataset2project[$datasetUuid]);
            if ($projectId != null) {
                return $projectId;
            }
        }

        $projectId = $this->findProjectForDatasetThrough($datasetUuid, $this->dataset2files, $this->datafile2project);
        if ($projectId != null) {
            return $projectId;
        }

        $projectId = $this->findProjectForDatasetThrough($datasetUuid, $this->dataset2entities, $this->entity2project);
        if ($projectId != null) {
            return $projectId;
        }

        return null;
    }

    private function findProjectIdForUuid($uuid)
    {
        $project = ItemCache::findProject($uuid);
        if ($project != null) {
            return $project->id;
        }

        return null;
    }

    private function findProjectForDatasetThrough($datasetUuid, $dataset2itemMap, $item2projectMap)
    {
        if (isset($dataset2itemMap[$datasetUuid])) {
            if (sizeof($dataset2itemMap[$datasetUuid]) != 0) {
                $idToLookup = $dataset2itemMap[$datasetUuid][0];
                if (isset($item2projectMap[$idToLookup])) {
                    $projectUuid = $item2projectMap[$idToLookup];
                    $projectId = $this->findProjectIdForUuid($projectUuid);
                    if ($projectId != null) {
                        return $projectId;
                    }
                }
            }
        }

        return null;
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

    private function addDownloads(Dataset $ds, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            Download::create([
                'who'               => "unknown_{$i}",
                'downloadable_type' => Dataset::class,
                'downloadable_id'   => $ds->id,
            ]);
        }
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

    private function addWorkflow(Dataset $dataset)
    {
        if (array_key_exists($dataset->uuid, $this->dataset2workflow)) {
            $wf = [];
            $wf['name'] = 'Experiment Workflow';
            $wf['workflow'] = $this->dataset2workflow[$dataset->uuid];
            $createdWorkflow = ($this->createWorkflowAction)($wf, $dataset->project_id, $dataset->owner_id);
            ($this->updateDatasetWorkflowSelectionAction)($createdWorkflow->id, $dataset->project_id, $dataset);
        }
    }
}