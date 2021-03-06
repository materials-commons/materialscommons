<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\CreateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\CreateExperimentRequest;
use App\Http\Resources\Experiments\ExperimentResource;

class CreateExperimentApiController extends Controller
{
    public function __invoke(CreateExperimentRequest $request, CreateExperimentAction $createExperimentAction)
    {
        $validated = $request->validated();
        $experiment = $createExperimentAction($validated);

        return (new ExperimentResource($experiment))->response()->setStatusCode(201);
    }
}
