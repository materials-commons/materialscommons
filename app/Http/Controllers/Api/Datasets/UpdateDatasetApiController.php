<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use Illuminate\Support\Arr;

class UpdateDatasetApiController extends Controller
{
    public function __invoke(DatasetRequest $request, UpdateDatasetAction $updateDatasetAction, Dataset $dataset)
    {
        $attrs = Arr::except($request->validated(), ['project_id']);
        return new DatasetResource($updateDatasetAction($attrs, $dataset));
    }
}
