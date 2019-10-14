<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\CreateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Project;

class StoreDatasetWebController extends Controller
{
    public function __invoke(DatasetRequest $request, Project $project)
    {
        $createDatasetAction = new CreateDatasetAction(auth()->id());
        $validated = $request->validated();
        $dataset = $createDatasetAction($validated);

        return redirect(route('projects.datasets.files.edit', [$project, $dataset]));
    }
}
