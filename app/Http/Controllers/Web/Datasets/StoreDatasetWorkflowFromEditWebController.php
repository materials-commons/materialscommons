<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UpdateDatasetWorkflowSelectionAction;
use App\Actions\Workflows\CreateWorkflowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\CreateWorkflowRequest;
use App\Models\Dataset;

class StoreDatasetWorkflowFromEditWebController extends Controller
{
    public function __invoke(CreateWorkflowRequest $request, CreateWorkflowAction $createWorkflowAction,
        UpdateDatasetWorkflowSelectionAction $updateDatasetWorkflowSelectionAction, $projectId, Dataset $dataset)
    {
        $validated = $request->validated();
        $workflow = $createWorkflowAction($validated, $projectId, auth()->id());
        $updateDatasetWorkflowSelectionAction($workflow->id, $projectId, $dataset);
        return redirect(route('projects.datasets.workflows.edit', [$projectId, $dataset]));
    }
}
