<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\DeleteExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class DestroyExperimentWebController extends Controller
{
    public function __invoke(DeleteExperimentAction $deleteExperimentAction, Project $project, Experiment $experiment)
    {
        $deleteExperimentAction($experiment);
        return redirect(route('projects.show', [$project]));
    }
}
