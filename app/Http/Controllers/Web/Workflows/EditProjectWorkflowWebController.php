<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workflow;

class EditProjectWorkflowWebController extends Controller
{
    public function __invoke(Project $project, Workflow $workflow)
    {
        return view('app.projects.workflows.edit', compact('project', 'workflow'));
    }
}
