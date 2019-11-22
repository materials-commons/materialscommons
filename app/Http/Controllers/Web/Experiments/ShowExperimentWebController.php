<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class ShowExperimentWebController extends Controller
{
    public function __invoke($projectId, $experimentId)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        $experiment = Experiment::with('workflows')->findOrFail($experimentId);
        return view('app.projects.experiments.show', compact('project', 'experiment'));
    }
}
