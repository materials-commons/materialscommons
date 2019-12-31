<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateProjectWorkflowWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.workflows.create', compact('project'));
    }
}
