<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Project;

class IndexProjectWorkflowsWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with('workflows')->findOrFail($projectId);
        $workflows = $project->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        return view('app.projects.workflows.index', compact('project', 'workflows'));
    }
}
