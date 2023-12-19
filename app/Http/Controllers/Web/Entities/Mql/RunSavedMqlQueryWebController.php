<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Actions\Mql\RunMqlQueryAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use App\Models\SavedQuery;
use App\Traits\Entities\EntityAndAttributeQueries;
use App\Traits\Mql\MqlQueryBuilder;

class RunSavedMqlQueryWebController extends Controller
{
    use MqlQueryBuilder;
    use EntityAndAttributeQueries;

    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities,
        RunMqlQueryAction $runMqlQueryAction, Project $project, SavedQuery $query)
    {
        $activities = $this->getProjectExperimentalActivities($project->id);

        $entities = Entity::with(['activities', 'experiments'])
                          ->where('project_id', $project->id)
                          ->get();

        $processAttributes = $this->getProcessAttributes($project->id);

        $sampleAttributes = $this->getSampleAttributes($project->id);

        $queryResults = $runMqlQueryAction->runQuery($query->query, $project);
        $entities = $runMqlQueryAction->filterEntitiesUsingQueryResults($entities, $queryResults);

        session()->flashInput($query->query);

        return view('app.projects.entities.index', [
            'showExperiment' => true,
            'project'           => $project,
            'activities'        => $activities,
            'entities'          => $entities,
            'processAttributes' => $processAttributes,
            'sampleAttributes'  => $sampleAttributes,
            'query'             => $this->buildMqlQueryText($query->query),
            'queries'           => SavedQuery::where('owner_id', auth()->id())
                                             ->where('project_id', $project->id)
                                             ->get(),
            'usedActivities'    => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
