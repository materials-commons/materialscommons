<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class CreateWorkflowWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.workflows.create', compact('project', 'experiment'));
    }
}
