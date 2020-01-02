<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateProjectEntityWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        return view('app.projects.entities.create', compact('project'));
    }
}
