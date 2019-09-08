<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\UpdateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\UpdateExperimentRequest;
use App\Http\Resources\Experiments\ExperimentResource;
use App\Models\Experiment;

class UpdateExperimentApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Experiments\UpdateExperimentRequest  $request
     *
     * @param  \App\Actions\Experiments\UpdateExperimentAction  $updateExperimentAction
     * @param  \App\Models\Experiment  $experiment
     *
     * @return \App\Http\Resources\Experiments\ExperimentResource
     */
    public function __invoke(UpdateExperimentRequest $request, UpdateExperimentAction $updateExperimentAction, Experiment $experiment)
    {
        $validated = $request->validated();
        $experiment = $updateExperimentAction($validated, $experiment);

        return new ExperimentResource($experiment);
    }
}
