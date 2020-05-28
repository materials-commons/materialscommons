<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Workflows\UpdateWorkflowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\UpdateWorkflowRequest;
use App\Models\Dataset;
use App\Models\Workflow;

class UpdateDatasetWorkflowFromEditWebController extends Controller
{

    public function __invoke(UpdateWorkflowRequest $request, UpdateWorkflowAction $updateWorkflowAction, $projectId,
        Dataset $dataset, Workflow $workflow)
    {
        $validated = $request->validated();
        $updateWorkflowAction($workflow, $validated);
        return redirect(route('projects.datasets.workflows.edit', [$projectId, $dataset]));
    }

}
