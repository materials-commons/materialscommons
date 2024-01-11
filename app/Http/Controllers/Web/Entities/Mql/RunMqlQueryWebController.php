<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Actions\Mql\RunMqlQueryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Entity;
use App\Models\Project;
use App\Models\SavedQuery;
use App\Traits\Entities\EntityAndAttributeQueries;
use App\Traits\Mql\MqlQueryBuilder;


class RunMqlQueryWebController extends Controller
{
    use MqlQueryBuilder;
    use EntityAndAttributeQueries;

    public function __invoke(MqlSelectionRequest $request, CreateUsedActivitiesForEntitiesAction $createUsedActivities,
                             RunMqlQueryAction   $runMqlQueryAction, Project $project)
    {
        $category = $request->input("category");

        $validated = $request->validated();

        $entities = Entity::with(['activities', 'experiments'])
                          ->where('project_id', $project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($project->id, $entities);


        $processAttributes = $this->getProcessAttributes($project->id);

        $sampleAttributes = $this->getSampleAttributes($project->id);

        $queryResults = $runMqlQueryAction->runQuery($validated, $project);
        $entities = $runMqlQueryAction->filterEntitiesUsingQueryResults($entities, $queryResults);

        $request->flash();

        return view('app.projects.entities.index', [
            'category'                => $category,
            'showExperiment'          => true,
            'project'                 => $project,
            'activities'              => $activities,
            'entities'                => $entities,
            'processAttributes'       => $processAttributes,
            'sampleAttributes'        => $sampleAttributes,
            'processAttributeDetails' => $this->getProcessAttrDetails($project->id),
            'sampleAttributeDetails'  => $this->getSampleAttrDetails($project->id),
            'query'                   => $this->buildMqlQueryText($validated),
            'queries'                 => SavedQuery::where('owner_id', auth()->id())
                                                   ->where('project_id', $project->id)
                                                   ->get(),
            'usedActivities'          => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
