<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetWorkflowSelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\UpdateDatasetWorkflowRequest;
use App\Models\Dataset;

class UpdateDatasetWorkflowSelectionApiController extends Controller
{
    public function __invoke(UpdateDatasetWorkflowRequest $request,
        UpdateDatasetWorkflowSelectionAction $updateDatasetWorkflowSelectionAction, Dataset $dataset)
    {
        $validated = $request->validated();
        $dataset = $updateDatasetWorkflowSelectionAction($validated["workflow_id"], $dataset);
        return $dataset;
    }
}
