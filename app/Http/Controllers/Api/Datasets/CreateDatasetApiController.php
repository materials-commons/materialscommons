<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\CreateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Http\Resources\Datasets\DatasetResource;

class CreateDatasetApiController extends Controller
{
    public function __invoke(DatasetRequest $request)
    {
        $validated = $request->validated();
        $createDatasetAction = new CreateDatasetAction(auth()->id());
        $dataset = $createDatasetAction($validated);
        return new DatasetResource($dataset);
    }
}
