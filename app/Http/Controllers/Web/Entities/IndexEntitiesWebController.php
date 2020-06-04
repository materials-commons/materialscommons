<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class IndexEntitiesWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $activities = DB::table('activities')
                        ->select('name')
                        ->where('project_id', $project->id)
                        ->distinct()
                        ->orderBy('name')
                        ->get();

        $entities = Entity::with('activities')
                          ->where('project_id', $project->id)
                          ->get();

        return view('app.projects.entities.index', [
            'project'        => $project,
            'activities'     => $activities,
            'entities'       => $entities,
            'usedActivities' => $this->createUsedActivities($entities, $activities),
        ]);
    }

    private function createUsedActivities($entities, $activities)
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
