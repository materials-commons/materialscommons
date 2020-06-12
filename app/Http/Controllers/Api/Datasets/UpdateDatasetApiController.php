<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use App\Models\Project;

class UpdateDatasetApiController extends Controller
{
    public function __invoke(DatasetRequest $request, UpdateDatasetAction $updateDatasetAction, Project $project,
        Dataset $dataset)
    {
        return new DatasetResource($updateDatasetAction($request->validated(), $dataset));
    }
}
