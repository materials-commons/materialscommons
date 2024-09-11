<?php

namespace App\Http\Controllers\Web\DataHQ\ComputationsHQ;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\DTO\DataExplorerState;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use function session;
use function view;

class IndexComputationsHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $deState = session("p:{$project->id}:de:state", new DataExplorerState());
        $deState->dataFor = "p:{$project->id}:de:state";
        session(["p:{$project->id}:de:state" => $deState]);
        session(["{$project->id}:de:data-for" => $deState->dataFor]);

        return view('app.projects.datahq.computationshq.index', [
            'project'  => $project,
            'deState'  => $deState,
            'query'    => '',
            'category' => 'experimental',
        ]);
    }
}
