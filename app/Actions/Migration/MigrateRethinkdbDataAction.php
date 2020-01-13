<?php

namespace App\Actions\Migration;

use App\Enums\ExperimentStatus;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Datadirs2ndTime
{
}

class MigrateRethinkdbDataAction
{
    private $orderToProcessObjectDumpFiles = [
        ['users.json' => User::class],
        ['projects.json' => Project::class],
        ['experiments.json' => Experiment::class],
        ['datadirs.json' => File::class],
        ['datadirs.json' => Datadirs2ndTime::class],
        //        ['datafiles.json' => File::class],
        ['samples.json' => Entity::class],
        ['processes.json' => Activity::class],
        //        ['properties.json' => Attribute::class],
        //        ['measurements.json' => AttributeValue::class],
        //        ['setupproperties.json' => Attribute::class], // special handling needed
        //        ['datasets.json' => Dataset::class]
        // Need propertysets here...
    ];

    private $orderToProcessJoinDumpFiles = [];

    private $placeHolderProject;
    private $placeHolderUser;
    private $pathToDumpFiles;
    private $knownItems;

    public function __construct($pathToDumpFiles)
    {
        $this->pathToDumpFiles = $pathToDumpFiles;
    }

    public function __invoke()
    {
        $this->createPlaceholders();

        $this->inOrderProcessDumpFiles();

        $this->removePlaceholders();
    }

    private function createPlaceholders()
    {
        $this->placeHolderUser = factory(User::class)->create();
        $this->placeHolderProject = factory(Project::class)->create([
            'owner_id' => $this->placeHolderUser->id,
        ]);
    }

    private function removePlaceholders()
    {
        $this->placeHolderProject->delete();
        $this->placeHolderUser->delete();
    }

    private function inOrderProcessDumpFiles()
    {
        foreach ($this->orderToProcessObjectDumpFiles as $dumpFile) {
            if (!$this->loadObjectDumpFile($dumpFile)) {
                return;
            }
        }

        foreach ($this->orderToProcessJoinDumpFiles as $dumpFile) {
            $this->loadJoinDumpFile($dumpFile);
        }
    }

    private function loadObjectDumpFile($dumpFile)
    {
        $file = key($dumpFile);
        $model = $dumpFile[$file];

        $this->performDumpFileSetup($file);

        $dumpFilePath = "{$this->pathToDumpFiles}/${file}";
        echo "\nLoading file {$dumpFilePath}\n";
        $handle = fopen($dumpFilePath, "r");

        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }

            $data = $this->decodeLine($line);
            try {
                $this->loadDataForModel($model, $file, $data);
            } catch (\Exception $e) {
                echo "Error loading data {$e->getMessage()}, model {$model}, file {$file}, line {$line}\n";
                return false;
            }
        }

        fclose($handle);

        $this->performCleanupForDumpfile($file);
        return true;
    }

    private function performDumpFileSetup($dumpFile)
    {
        switch ($dumpFile) {
            case 'samples.json':
                return $this->setupItemMapping("project2sample.json", "sample_id", "project_id");
            case 'processes.json':
                return $this->setupItemMapping("project2process.json", "process_id", "project_id");
            case 'experiments.json':
                return $this->setupItemMapping('project2experiment.json', 'experiment_id', 'project_id');
            case 'datadirs.json':
                return $this->setupItemMapping('project2datadir.json', 'datadir_id', 'project_id');
            default:
                return true;
        }
    }

    private function performCleanupForDumpfile($dumpFile)
    {
        switch ($dumpFile) {
            case 'experiments.json':
            case 'datadirs.json':
            case 'processes.json':
            case 'samples.json':
                return $this->cleanupItemMapping();
            default:
                return true;
        }
    }


    private function setupItemMapping($file, $key, $valueKey)
    {
        $this->knownItems = [];
        $project2sampleDumpfile = "{$this->pathToDumpFiles}/${file}";
        $handle = fopen($project2sampleDumpfile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }
            $data = $this->decodeLine($line);
            $this->knownItems[$data[$key]] = $data[$valueKey];
        }

        fclose($handle);

        return true;
    }

    private function cleanupItemMapping()
    {
        $this->knownItems = [];
    }

    private function loadJoinDumpFile($dumpFile)
    {
        error_log('process join file');
    }

    private function ignoreLine($line)
    {
        if ($line == '') {
            return true;
        }

        if ($line[0] == '[') {
            return true;
        }

        if ($line[0] == ']') {
            return true;
        }

        return false;
    }

    private function decodeLine($line)
    {
        $l = trim($line);
        if ($l[strlen($l) - 1] == ',') {
            $l = substr_replace($l, "", -1);
        }

        return json_decode($l, true);
    }

    private function loadDataForModel($model, $file, $data)
    {
        switch ($model) {
            case User::class:
                return $this->loadDataForUser($data);

            case Project::class:
                return $this->loadDataForProject($data);

            case Experiment::class:
                return $this->loadDataForExperiment($data);

            case File::class:
                if ($file == "datadirs.json") {
                    return $this->loadDataForDirectory($data);
                }

                return $this->loadDataForFile($data);

            case Datadirs2ndTime::class:
                return $this->matchupDirectories($data);

            case Entity::class:
                return $this->loadDataForEntity($data);

            case Activity::class:
                return $this->loadDataForActivity($data);

            case Attribute::class:
                if ($file == 'properties.json') {
                    return $this->loadDataForAttribute($data);
                }

                return $this->loadDataForActivitySetting($data);

            case AttributeValue::class:
                return $this->loadDataForAttributeValue($data);

            case Dataset::class:
                return $this->loadDataForDataset($data);

            default:
                throw new \Exception("Unknown model to load {$model}");
        }
    }

    private function loadDataForUser($data)
    {
        $modelData = [];
        $modelData["uuid"] = $data["id"];
        $modelData["email"] = $data["email"];
        $modelData["name"] = $data["name"];

        if (isset($data["description"])) {
            $modelData["description"] = $data["description"];
        }

        if (isset($data["affiliation"])) {
            $modelData["affiliations"] = $data["affiliation"];
        }

        $modelData["api_token"] = Str::random(60);
        $modelData["password"] = Hash::make(Str::random(24));
        echo "Adding user {$modelData['email']}/{$modelData['name']}\n";

        return User::create($modelData);
    }

    private function loadDataForProject($data)
    {
        $modelData = $this->createCommonModelData($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['is_active'] = true;
        echo "Adding project {$modelData['name']}\n";

        return Project::create($modelData);
    }

    private function loadDataForExperiment($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['status'] = $this->mapExperimentStatus($data);

        echo "Adding experiment {$modelData['name']}\n";

        return Experiment::create($modelData);
    }

    private function mapExperimentStatus($data)
    {
        if (!isset($data['status'])) {
            return ExperimentStatus::InProgress;
        }

        switch ($data['status']) {
            case 'done':
                return ExperimentStatus::Done;
            case 'on-hold':
                return ExperimentStatus::OnHold;
            default:
                return ExperimentStatus::InProgress;
        }
    }

    private function loadDataForDirectory($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);
        if ($modelData == null) {
            return null;
        }

        $nameWithPath = $modelData['name'];
        $modelData['name'] = basename($nameWithPath);
        $modelData['path'] = $this->removeProjectFromPathName($nameWithPath);
        $modelData['mime_type'] = 'directory';
        $modelData['media_type_description'] = 'directory';
        $modelData['disk'] = 'local';

        echo "Adding directory {$modelData['name']}, path: {$modelData['path']}\n";

        return File::create($modelData);
    }

    private function removeProjectFromPathName($nameWithPath)
    {
        // return everything from first '/' to end of string
        return substr($nameWithPath, strpos($nameWithPath, '/'));
    }

    private function matchupDirectories($data)
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

    /*
     * {
    "uploaded": 7048,
    "description": "",
    "parent": "",
    "checksum": "71a87031b30ebbaee9c2df78c6bdf6fa",
    "mtime": {
      "timezone": "+00:00",
      "$reql_type$": "TIME",
      "epoch_time": 1492059869.188
    },
    "mediatype": {
      "mime": "text/plain",
      "description": "Text"
    },
    "name": "traj.txt",
    "current": true,
    "otype": "datafile",
    "usesid": "f42a2e3e-2bf3-47d5-a489-bd375f5415e7",
    "owner": "anirudh@engineering.ucsb.edu",
    "birthtime": {
      "timezone": "+00:00",
      "$reql_type$": "TIME",
      "epoch_time": 1492059869.188
    },
    "atime": {
      "timezone": "+00:00",
      "$reql_type$": "TIME",
      "epoch_time": 1492059869.188
    },
    "id": "000000b4-e3dd-4d5c-b7b5-a077366135d0",
    "size": 7048
  },

     */
    private function loadDataForFile($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['current'] = $data['current'];
        $modelData['mime_type'] = $data['mediatype']['mime'];
        $modelData['media_type_description'] = $data['mediatype']['description'];
        $modelData['size'] = $data['size'];
        $modelData['current'] = $data['current'];
        $modelData['uses_uuid'] = $data['usesid'];
        $modelData['checksum'] = $data['checksum'];
        return true;
    }

    private function loadDataForEntity($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);

        if ($modelData == null) {
            return null;
        }

        echo "Adding Sample {$modelData['name']}\n";

        return Entity::create($modelData);
    }

    private function loadDataForActivity($data)
    {
        $modelData = $this->createModelDataForKnownItems($data);

        if ($modelData == null) {
            return null;
        }

        echo "Adding Process {$modelData['name']}\n";

        return Activity::create($modelData);
    }

    private function createModelDataForKnownItems($data)
    {
        if (!isset($this->knownItems[$data['id']])) {
            return null;
        }

        $modelData = $this->createCommonModelData($data);

        if ($modelData == null) {
            return null;
        }

        $project = Project::where('uuid', $this->knownItems[$data['id']])->first();
        if ($project == null) {
            return null;
        }

        $modelData['project_id'] = $project->id;
        return $modelData;
    }

    private function createCommonModelData($data)
    {
        $modelData = [];
        $modelData["uuid"] = $data["id"];
        $modelData["name"] = $data["name"];
        if (isset($data['description'])) {
            $modelData['description'] = $data['description'];
        } else {
            if (isset($data['note'])) {
                $modelData['description'] = $data['note'];
            }
        }

        $user = User::where('email', $data['owner'])->first();
        if ($user == null) {
            return null;
        }

        $modelData['owner_id'] = $user->id;

        return $modelData;
    }

    private function loadDataForAttribute($data)
    {
        return true;
    }

    private function loadDataForAttributeValue($data)
    {
        return true;
    }

    private function loadDataForActivitySetting($data)
    {
        return true;
    }

    private function loadDataForDataset($data)
    {
        return true;
    }
}