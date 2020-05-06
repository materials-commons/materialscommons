<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with(['owner', 'experiments'])->withCount('users')->where('id', $projectId)->first();
        return view('app.projects.show', compact('project'));
    }
}
