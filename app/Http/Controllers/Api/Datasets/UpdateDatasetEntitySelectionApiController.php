<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\UpdateDatasetEntitySelectionRequest;
use App\Models\Dataset;

class UpdateDatasetEntitySelectionApiController extends Controller
{
    public function __invoke(UpdateDatasetEntitySelectionRequest $request,
        UpdateDatasetEntitySelectionAction $updateDatasetEntitySelectionAction, Dataset $dataset)
    {
        $validated = $request->validated();
        $dataset = $updateDatasetEntitySelectionAction($validated["entity_id"], $dataset);
        return $dataset;
    }
}
