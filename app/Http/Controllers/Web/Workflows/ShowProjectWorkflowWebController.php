<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workflow;

class ShowProjectWorkflowWebController extends Controller
{
    public function __invoke(Project $project, Workflow $workflow)
    {
        return view('app.projects.workflows.show', compact('project', 'workflow'));
    }
}
