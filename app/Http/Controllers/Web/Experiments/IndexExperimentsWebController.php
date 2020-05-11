<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Project;

class IndexExperimentsWebController extends Controller
{
    public function __invoke($projectId)
    {
        return view('app.projects.experiments.index', [
            'project' => Project::with('experiments.owner')->findOrFail($projectId),
        ]);
    }
}
