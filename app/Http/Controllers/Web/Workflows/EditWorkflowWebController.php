<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Workflow;

class EditWorkflowWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment, Workflow $workflow)
    {
        return view('app.projects.experiments.workflows.edit', compact('project', 'experiment', 'workflow'));
    }
}
