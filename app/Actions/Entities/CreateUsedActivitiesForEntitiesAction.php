<?php

namespace App\Actions\Entities;

use App\Models\Entity;
use Illuminate\Support\Facades\DB;

class CreateUsedActivitiesForEntitiesAction
{
    public function execute($projectId)
    {
        $items = new \stdClass();

        $items->activities = DB::table('activities')
                               ->select('name')
                               ->where('project_id', $projectId)
                               ->distinct()
                               ->orderBy('name')
                               ->get();

        $items->entities = Entity::with('activities')
                                 ->where('project_id', $projectId)
                                 ->get();
        $items->usedActivities = $this->createdUsedActivities($items->entities, $items->activities);
        return $items;
    }

    private function createdUsedActivities($entities, $activities)
    {
        $usedActivitiesForEntities = [];
        foreach ($entities as $entity) {
            $usedActivitiesForEntities[$entity->id] = [];
            foreach ($activities as $activity) {
                if ($this->entityHasActivity($entity, $activity->name)) {
                    array_push($usedActivitiesForEntities[$entity->id], true);
                } else {
                    array_push($usedActivitiesForEntities[$entity->id], false);
                }
            }
        }

        return $usedActivitiesForEntities;
    }

    private function entityHasActivity($entity, $name)
    {
        return $entity->activities->contains('name', $name);
    }
}