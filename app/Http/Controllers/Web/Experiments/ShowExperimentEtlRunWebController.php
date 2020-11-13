<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\EtlRun;
use App\Models\Experiment;
use App\Models\Project;

class ShowExperimentEtlRunWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment, EtlRun $etlRun)
    {
        return view('app.projects.experiments.etlrun.show', [
            'project'    => $project,
            'experiment' => $experiment,
            'etlRun'     => $etlRun,
        ]);
    }
}
