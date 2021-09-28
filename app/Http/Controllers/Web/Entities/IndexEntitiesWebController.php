<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use App\Models\SavedQuery;
use App\Traits\Entities\EntityAndAttributeQueries;

class IndexEntitiesWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project)
    {
        $activities = $this->getProjectActivities($project->id);

        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('project_id', $project->id)
                          ->get();

        $processAttributes = $this->getProcessAttributes($project->id);

        $sampleAttributes = $this->getSampleAttributes($project->id);

        $query = "";

        return view('app.projects.entities.index', [
            'project'           => $project,
            'activities'        => $activities,
            'entities'          => $entities,
            'processAttributes' => $processAttributes,
            'sampleAttributes'  => $sampleAttributes,
            'query'             => $query,
            'queries'           => SavedQuery::where('owner_id', auth()->id())
                                             ->where('project_id', $project->id)
                                             ->get(),
            'usedActivities'    => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
