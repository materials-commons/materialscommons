<?php

namespace App\Actions\Entities;

use App\Models\Activity;

class CreateUsedActivitiesForEntitiesAction
{
    public function execute($activities, $entities)
    {
        return $this->createdUsedActivities($entities, $activities);
    }

    /*
     * Loop through entities and for each entity create a map of the list of activities and their count.
     * For example, if activites = [a,b], and entities in = [e1 (id 1), e2 (id 2)], and only e1 is using activities a and b, then
     * you end up with:
     * $usedActivitiesForEntities = [
     *     1 => [1,1],
     *     2 => [0,0]
     * ]
     */
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