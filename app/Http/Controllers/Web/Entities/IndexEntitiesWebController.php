<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class IndexEntitiesWebController extends Controller
{
    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project)
    {
        $activities = DB::table('activities')
                        ->select('name')
                        ->where('project_id', $project->id)
                        ->where('name', '<>', 'Create Samples')
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
            'usedActivities' => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
