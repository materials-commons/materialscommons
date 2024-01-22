<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;

class IndexEntitiesWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, CreateUsedActivitiesForEntitiesAction $createUsedActivities,
                             Project $project)
    {
        $category = $request->input("category");

        auth()->user()->addToRecentlyAccessedProjects($project);

        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', $category)
                          ->where('project_id', $project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($project->id, $entities);
        $usedActivities = $createUsedActivities->execute($activities, $entities);

        return view('app.projects.entities.index', [
            'showExperiment'          => true,
            'category'       => $category,
            'project'        => $project,
            'activities'     => $activities,
            'entities'       => $entities,
            'usedActivities' => $usedActivities,
        ]);
    }
}
