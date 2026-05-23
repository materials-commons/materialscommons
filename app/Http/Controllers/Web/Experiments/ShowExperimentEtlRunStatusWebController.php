<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\EtlRun;
use App\Models\Experiment;
use App\Models\Project;
use function abort_unless;
use function view;

class ShowExperimentEtlRunStatusWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment, EtlRun $etlRun)
    {
        abort_unless(
            $etlRun->etlable_type === Experiment::class && (int) $etlRun->etlable_id === (int) $experiment->id,
            404
        );

        return view('app.projects.experiments.etlrun.status', [
            'project'    => $project,
            'experiment' => $experiment,
            'etlRun'     => $etlRun,
        ]);
    }
}
