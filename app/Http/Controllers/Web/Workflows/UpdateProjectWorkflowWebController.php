<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Actions\Workflows\UpdateWorkflowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\UpdateWorkflowRequest;
use App\Models\Workflow;

class UpdateProjectWorkflowWebController extends Controller
{
    public function __invoke(UpdateWorkflowRequest $request, UpdateWorkflowAction $updateWorkflowAction, $projectId,
        Workflow $workflow)
    {
        $validated = $request->validated();
        $updateWorkflowAction($workflow, $validated);
        return redirect(route('projects.workflows.index', [$projectId]));
    }
}
