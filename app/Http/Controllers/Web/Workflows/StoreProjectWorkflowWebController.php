<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Actions\Workflows\CreateWorkflowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\CreateWorkflowRequest;

class StoreProjectWorkflowWebController extends Controller
{
    public function __invoke(CreateWorkflowRequest $request, CreateWorkflowAction $createWorkflowAction, $projectId)
    {
        $validated = $request->validated();
        $createWorkflowAction($validated, $projectId, auth()->id());
        return redirect(route('projects.workflows.index', [$projectId]));
    }
}
