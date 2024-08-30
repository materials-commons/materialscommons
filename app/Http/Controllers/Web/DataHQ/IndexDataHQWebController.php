<?php

namespace App\Http\Controllers\Web\DataHQ;

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
        return view('app.projects.datahq.index', [
            'project'     => $project,
            'experiments' => $experiments,
            'activities'  => $activities,
            'query'       => '',
        ]);
    }
}
