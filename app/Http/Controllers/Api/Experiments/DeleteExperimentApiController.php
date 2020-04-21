<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\DeleteExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;

class DeleteExperimentApiController extends Controller
{
    public function __invoke(DeleteExperimentAction $deleteExperimentAction, $projectId, Experiment $experiment)
    {
        $deleteExperimentAction($experiment);
    }
}
