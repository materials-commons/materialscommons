<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\UpdateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\UpdateExperimentRequest;
use App\Http\Resources\Experiments\ExperimentResource;
use App\Models\Experiment;
use Illuminate\Support\Arr;

class UpdateExperimentApiController extends Controller
{
    public function __invoke(UpdateExperimentRequest $request, UpdateExperimentAction $updateExperimentAction,
        Experiment $experiment)
    {
        $validated = Arr::except($request->validated(), ['project_id']);
        $experiment = $updateExperimentAction($validated, $experiment);

        return new ExperimentResource($experiment);
    }
}
