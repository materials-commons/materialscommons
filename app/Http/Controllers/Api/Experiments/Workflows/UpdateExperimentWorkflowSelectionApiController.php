<?php

namespace App\Http\Controllers\Api\Experiments\Workflows;

use App\Actions\Experiments\Workflows\UpdateExperimentWorkflowSelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\Workflows\UpdateExperimentWorkflowSelectionRequest;
use App\Models\Experiment;

class UpdateExperimentWorkflowSelectionApiController extends Controller
{
    public function __invoke(UpdateExperimentWorkflowSelectionRequest $request,
        UpdateExperimentWorkflowSelectionAction $updateExperimentWorkflowSelectionAction, Experiment $experiment)
    {
        $validated = $request->validated();
        $experiment = $updateExperimentWorkflowSelectionAction->execute($validated['workflow_id'],
            $validated['project_id'], $experiment);
        return $experiment;
    }
}
