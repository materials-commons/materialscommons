<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\CreateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Project;

class StoreDatasetWebController extends Controller
{
    public function __invoke(DatasetRequest $request, CreateDatasetAction $createDatasetAction, Project $project)
    {
        $validated = $request->validated();
        dd($validated);
        $action = $validated["action"];
        unset($validated["action"]);
        $dataset = $createDatasetAction->execute($validated, $project->id, auth()->id());
        if ($action === "save") {
            return redirect(route('projects.datasets.index', [$project]));
        } else {
            return redirect(route('projects.datasets.create-data', [$project, $dataset]));
        }
    }
}
