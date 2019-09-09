<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateExperimentWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.experiments.create', compact('project'));
    }
}
