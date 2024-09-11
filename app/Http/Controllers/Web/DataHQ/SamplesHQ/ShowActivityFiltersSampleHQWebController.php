<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use function ray;
use function session;
use function view;

class ShowActivityFiltersSampleHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        $activities = Activity::where('project_id', $project->id)->get();
        $dataFor = session("{$project->id}:de:data-for");
        $deState = session($dataFor);
        return view('app.projects.datahq.sampleshq.index', [
            'project'     => $project,
            'experiments' => $experiments,
            'activities'  => $activities,
            'deState'     => $deState,
            'query'       => '',
        ]);
    }
}
