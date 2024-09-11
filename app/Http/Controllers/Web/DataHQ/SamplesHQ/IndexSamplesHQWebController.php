<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\DTO\DataExplorerState;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;
use function session;
use function view;

class IndexSamplesHQWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        $deState = session("p:{$project->id}:de:state", new DataExplorerState());
        $deState->dataFor = "p:{$project->id}:de:state";
        session(["p:{$project->id}:de:state" => $deState]);
        session(["{$project->id}:de:data-for" => $deState->dataFor]);
        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', 'experimental')
                          ->where('project_id', $project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($project->id, $entities);
        $createUsedActivities = new  CreateUsedActivitiesForEntitiesAction();
        $usedActivities = $createUsedActivities->execute($activities, $entities);
        $sampleAttributes = $this->getSampleAttributes($project->id);
        $processAttributes = $this->getProcessAttributes($project->id);
        return view('app.projects.datahq.sampleshq.index', [
            'project'           => $project,
            'experiments'       => $experiments,
            'activities'        => $activities,
            'deState'           => $deState,
            'query'             => '',
            'category'          => 'experimental',
            'entities'          => $entities,
            'usedActivities'    => $usedActivities,
            //            'sampleAttributes'  => $sampleAttributes,
            //            'processAttributes' => $processAttributes,
        ]);
    }
}
