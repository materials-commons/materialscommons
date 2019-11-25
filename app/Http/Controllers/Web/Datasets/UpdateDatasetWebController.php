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
        $action = $validated["action"];
        unset($validated["action"]);
        $dataset = $updateDatasetAction($validated, $dataset);
        if ($action === "save") {
            return redirect(route('projects.datasets.show', compact('project', 'dataset')));
        } elseif ($action === "files") {
            return redirect(route('projects.datasets.files.edit', compact('project', 'dataset')));
        } elseif ($action === "workflow") {
            return redirect(route('projects.datasets.workflows.edit', compact('project', 'dataset')));
        } else {
            return redirect(route('projects.datasets.samples.edit', compact('project', 'dataset')));
        }
    }
}
