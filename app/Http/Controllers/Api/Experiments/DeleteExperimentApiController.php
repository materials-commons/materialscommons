<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\DeleteExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;

class DeleteExperimentApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Experiments\DeleteExperimentAction  $deleteExperimentAction
     * @param  \App\Models\Experiment  $experiment
     *
     * @return void
     */
    public function __invoke(DeleteExperimentAction $deleteExperimentAction, Experiment $experiment)
    {
        $deleteExperimentAction($experiment);
    }
}
