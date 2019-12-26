<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetActivitySelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\UpdateDatasetActivityRequest;
use App\Models\Dataset;

class UpdateDatasetActivitySelectionApiController extends Controller
{
    public function __invoke(UpdateDatasetActivityRequest $request,
        UpdateDatasetActivitySelectionAction $updateDatasetActivitySelectionAction, Dataset $dataset)
    {
        $validated = $request->validated();
        $dataset = $updateDatasetActivitySelectionAction($validated["activity_id"], $dataset);
        return $dataset;
    }
}
