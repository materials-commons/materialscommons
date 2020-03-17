<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Workflows\CreateWorkflowAndAddToDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\CreateWorkflowRequest;
use App\Models\Dataset;

class StoreDatasetWorkflowFromEditWebController extends Controller
{
    public function __invoke(CreateWorkflowRequest $request,
        CreateWorkflowAndAddToDatasetAction $createWorkflowAndAddToDatasetAction, $projectId, Dataset $dataset)
    {
        $validated = $request->validated();
        $createWorkflowAndAddToDatasetAction($validated, $projectId, auth()->id(), $dataset);
        return redirect(route('projects.datasets.workflows.edit', [$projectId, $dataset]));
    }
}
