<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\DTO\DataExplorerState;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexDataHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        $activities = Activity::where('project_id', $project->id)->get();
        $deState = session("p:{$project->id}:de:state", new DataExplorerState());
        $deState->dataFor = "p:{$project->id}:de:state";
        session(["p:{$project->id}:de:state" => $deState]);
        session(["{$project->id}:de:data-for" => $deState->dataFor]);
        return view('app.projects.datahq.index', [
            'project'     => $project,
            'experiments' => $experiments,
            'activities'  => $activities,
            'deState' => $deState,
            'query'       => '',
        ]);
    }
}
