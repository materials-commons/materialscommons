<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateExperimentWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $excelFiles = $project->files()->with('directory')->where('mime_type',
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")->get();
        return view('app.projects.experiments.create', compact('project', 'excelFiles'));
    }
}
