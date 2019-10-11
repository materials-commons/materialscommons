<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetSelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\UpdateDatasetFileSelectionRequest;
use App\Models\Dataset;

class UpdateDatasetFileSelectionApiController extends Controller
{
    public function __invoke(UpdateDatasetFileSelectionRequest $request, Dataset $dataset)
    {
        $validated = $request->validated();
        $updateDatasetFileSelectionAction = new UpdateDatasetSelectionAction();
        $dataset = $updateDatasetFileSelectionAction($validated, $dataset);
        return $dataset;
    }
}
