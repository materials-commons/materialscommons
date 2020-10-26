<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\AssignDoiToDatasetAction;
use App\Actions\Datasets\UpdateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Dataset;
use App\Models\Project;

class AssignDoiWebController extends Controller
{
    public function __invoke(DatasetRequest $request, UpdateDatasetAction $updateDatasetAction,
        AssignDoiToDatasetAction $assignDoiToDatasetAction, Project $project, Dataset $dataset)
    {
        $validated = $request->validated();
        unset($validated["action"]);
        $user = auth()->user();
        $dataset = $updateDatasetAction($validated, $dataset);
        $assignDoiToDatasetAction($dataset, $user);
        return redirect(route('projects.datasets.edit', [$project, $dataset]));
    }
}
