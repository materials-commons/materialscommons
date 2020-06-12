<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\CreateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Project;

class CreateDatasetApiController extends Controller
{
    public function __invoke(DatasetRequest $request, CreateDatasetAction $createDatasetAction, Project $project)
    {
        $validated = $request->validated();
        $dataset = $createDatasetAction->execute($validated, $project->id, auth()->id());
        return new DatasetResource($dataset);
    }
}
