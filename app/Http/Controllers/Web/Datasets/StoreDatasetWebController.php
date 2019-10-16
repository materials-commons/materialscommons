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
        $save = $validated["save"];
        unset($validated["save"]);
        $dataset = $createDatasetAction($validated);
        if ($save === "1") {
            return redirect(route('projects.datasets.index', compact('project')));
        }
        return redirect(route('projects.datasets.files.edit', compact('project', 'dataset')));
    }
}
