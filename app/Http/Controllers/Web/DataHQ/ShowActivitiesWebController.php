<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use function ray;
use function session;
use function view;

class ShowActivitiesWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        $activities = Activity::where('project_id', $project->id)->get();
        ray($request->session()->all());
        $dataFor = session("{$project->id}:de:data-for");
        ray("dataFor = {$dataFor}");
        $deState = session($dataFor);
        ray("deState =", $deState);
        return view('app.projects.datahq.index', [
            'project'     => $project,
            'experiments' => $experiments,
            'activities'  => $activities,
            'deState'     => $deState,
            'query'       => '',
        ]);
    }
}
