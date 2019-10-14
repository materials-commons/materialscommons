<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UpdateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Dataset;
use App\Models\Project;

class UpdateDatasetWebController extends Controller
{
    public function __invoke(DatasetRequest $request, UpdateDatasetAction $updateDatasetAction, Project $project,
        Dataset $dataset)
    {
        $validated = $request->validated();
        $dataset = $updateDatasetAction($validated, $dataset);
        return redirect(route('projects.datasets.files.edit', compact('project', 'dataset')));
    }
}
