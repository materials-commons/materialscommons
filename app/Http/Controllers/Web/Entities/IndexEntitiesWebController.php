<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use App\Models\SavedQuery;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexEntitiesWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, CreateUsedActivitiesForEntitiesAction $createUsedActivities,
                             Project $project)
    {
        $category = $request->input("category");

        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', $category)
                          ->where('project_id', $project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($project->id, $entities);

        $processAttributes = $this->getProcessAttributes($project->id);

        $sampleAttributes = $this->getSampleAttributes($project->id);

        $query = "";

        $usedActivities = $createUsedActivities->execute($activities, $entities);

        return view('app.projects.entities.index', [
            'category'          => $category,
            'showExperiment'          => true,
            'project'           => $project,
            'activities'        => $activities,
            'entities'          => $entities,
            'processAttributes' => $processAttributes,
            'sampleAttributes'  => $sampleAttributes,
            'processAttributeDetails' => $this->getProcessAttrDetails($project->id),
            'sampleAttributeDetails'  => $this->getSampleAttrDetails($project->id),
            'query'             => $query,
            'queries'           => SavedQuery::where('owner_id', auth()->id())
                                             ->where('project_id', $project->id)
                                             ->get(),
            'usedActivities'    => $usedActivities,
        ]);
    }
}
