<?php

namespace App\Actions\Entities;

use App\Models\Activity;

class CreateUsedActivitiesForEntitiesAction
{
    public function execute($activities, $entities)
    {
        return $this->createdUsedActivities($entities, $activities);
    }

    private function createdUsedActivities($entities, $activities)
    {
        $usedActivitiesForEntities = [];
        foreach ($entities as $entity) {
            $usedActivitiesForEntities[$entity->id] = [];
            foreach ($activities as $activity) {
                array_push($usedActivitiesForEntities[$entity->id],
                    $this->countActivityInEntity($activity->name, $entity));
            }
        }

        return $usedActivitiesForEntities;
    }

    private function countActivityInEntity($name, $entity)
    {
        return $entity->activities->filter(function (Activity $activity) use ($name) {
            return $activity->name === $name;
        })->count();
    }
}