<?php

namespace App\Actions\Migration;

use App\Models\Project;
use App\Models\User;

trait ItemCreater
{

    public function createModelDataForKnownItems($data, $knownItems)
    {
        if (!isset($knownItems[$data['id']])) {
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

    public function createCommonModelData($data)
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
