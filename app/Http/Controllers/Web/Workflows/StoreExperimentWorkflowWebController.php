<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Actions\Workflows\CreateWorkflowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\CreateWorkflowRequest;
use App\Models\Experiment;

class StoreExperimentWorkflowWebController extends Controller
{
    public function __invoke(CreateWorkflowRequest $request, CreateWorkflowAction $createWorkflowAction, $projectId,
        Experiment $experiment)
    {
        $validated = $request->validated();
        $createWorkflowAction($validated, $projectId, $experiment, auth()->id());
        return redirect(route('projects.experiments.show', [$projectId, $experiment]));
    }
}
