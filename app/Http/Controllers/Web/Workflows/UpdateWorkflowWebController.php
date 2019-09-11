<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Actions\Workflows\UpdateWorkflowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\UpdateWorkflowRequest;
use App\Models\Workflow;

class UpdateWorkflowWebController extends Controller
{
    public function __invoke(
        UpdateWorkflowRequest $request,
        UpdateWorkflowAction $updateWorkflowAction,
        $projectId,
        $experimentId,
        Workflow $workflow
    ) {
        $validated = $request->validated();
        $updateWorkflowAction($workflow, $validated['workflow']);
        return redirect(route('projects.experiments.show', [$projectId, $experimentId]));
    }
}
