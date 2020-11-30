<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\AssignDoiToDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use App\Models\Project;

class AssignDoiApiController extends Controller
{
    public function __invoke(AssignDoiToDatasetAction $assignDoiToDatasetAction, Project $project, Dataset $dataset)
    {
        $user = auth()->user();
        $dataset = $assignDoiToDatasetAction($dataset, $user);
        return new DatasetResource($dataset);
    }
}
