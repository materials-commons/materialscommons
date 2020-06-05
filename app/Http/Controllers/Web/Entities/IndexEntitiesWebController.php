<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Project;

class IndexEntitiesWebController extends Controller
{
    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project)
    {
        $used = $createUsedActivities->execute($project->id);
        return view('app.projects.entities.index', [
            'project'        => $project,
            'activities'     => $used->activities,
            'entities'       => $used->entities,
            'usedActivities' => $used->usedActivities,
        ]);
    }
}
