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
        $action = $validated["action"];
        unset($validated["action"]);
        $dataset = $createDatasetAction($validated);
        if ($action === "save") {
            return redirect(route('projects.datasets.index', [$project]));
        } elseif ($action === "files") {
            return redirect(route('projects.datasets.files.edit', [$project, $dataset]));
        } elseif ($action === "workflow") {
            return redirect(route('projects.datasets.workflows.edit', [$project, $dataset]));
        } elseif ($action === "processes") {
            return redirect(route('projects.datasets.activities.edit', [$project, $dataset]));
        } else {
            return redirect(route('projects.datasets.samples.edit', [$project, $dataset]));
        }
    }
}
