<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class ShowExperimentWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.show', compact('project', 'experiment'));
    }
}
