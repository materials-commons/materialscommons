<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class ShowReloadExperimentWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment)
    {
        $excelFiles = $project
            ->files()
            ->with('directory')
            ->where('mime_type', "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
            ->where('current', true)
            ->get();
        return view('app.projects.experiments.reload', compact('project', 'experiment', 'excelFiles'));
    }
}
