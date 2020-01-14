<?php

namespace App\Actions\Migration;

use App\Models\Project;
use App\Models\User;

trait ItemLoader
{

    use LineHandler;

    public $knownItems;
    public $knownItems2;

    public function setupItemMapping($file, $key, $valueKey, $use2nd = false)
    {
        $knownItems = [];
        $project2sampleDumpfile = "{$this->pathToDumpfiles}/${file}";
        $handle = fopen($project2sampleDumpfile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }
            $data = $this->decodeLine($line);
            $knownItems[$data[$key]] = $data[$valueKey];
        }

        fclose($handle);

        if ($use2nd) {
            $this->knownItems2 = $knownItems;
        } else {
            $this->knownItems = $knownItems;
        }
        return true;
    }

    public function cleanupItemMapping()
    {
        $this->knownItems = [];
        $this->knownItems2 = [];
        return true;
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

        if (isset($data['owner'])) {
            $user = User::where('email', $data['owner'])->first();
            if ($user == null) {
                return null;
            }
            $modelData['owner_id'] = $user->id;
        }

        return $modelData;
    }
}

