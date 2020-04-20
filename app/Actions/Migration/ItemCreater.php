<?php

namespace App\Actions\Migration;

trait ItemCreater
{

    public function createModelDataForKnownItems($data, $knownItems)
    {
        if (!isset($knownItems[$data['id']])) {
            return null;
        }

        $project = ItemCache::findProject($knownItems[$data['id']]);
        if ($project == null) {
            return null;
        }

        $modelData = $this->createCommonModelDataWithProject($data, $project);

        if ($modelData == null) {
            return null;
        }

        $modelData['project_id'] = $project->id;
        return $modelData;
    }

    public function createCommonModelData($data)
    {
        $modelData = [];
        $modelData["uuid"] = $data["id"];
        if (isset($data['name'])) {
            $modelData["name"] = $data["name"];
        } elseif (isset($data['title'])) {
            $modelData['name'] = $data['title'];
        }

        if (isset($data['description'])) {
            $modelData['description'] = $data['description'];
        } else {
            if (isset($data['note'])) {
                $modelData['description'] = $data['note'];
            }
        }

        if (isset($data['owner'])) {
            $user = ItemCache::findUser($data['owner']);
            if ($user == null) {
                echo "Failed looking up user {$data['owner']}\n";
                return null;
            }
            $modelData['owner_id'] = $user->id;
        }

        return $modelData;
    }

    private function createCommonModelDataWithProject($data, $project)
    {
        $modelData = [];
        $modelData["uuid"] = $data["id"];
        if (isset($data['name'])) {
            $modelData["name"] = $data["name"];
        } elseif (isset($data['title'])) {
            $modelData['name'] = $data['title'];
        }

        if (isset($data['description'])) {
            $modelData['description'] = $data['description'];
        } else {
            if (isset($data['note'])) {
                $modelData['description'] = $data['note'];
            }
        }

        if (isset($data['owner'])) {
            $user = ItemCache::findUser($data['owner']);
            $modelData['owner_id'] = $user != null ? $user->id : $project->owner_id;
        }

        return $modelData;
    }
}
