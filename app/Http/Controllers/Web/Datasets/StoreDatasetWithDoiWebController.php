<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\AssignDoiToDatasetAction;
use App\Actions\Datasets\CreateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Project;

class StoreDatasetWithDoiWebController extends Controller
{
    public function __invoke(DatasetRequest $request, CreateDatasetAction $createDatasetAction,
        AssignDoiToDatasetAction $assignDoiToDatasetAction, Project $project)
    {
        $validated = $request->validated();
//        $action = $validated["action"];
        unset($validated["action"]);
        $user = auth()->user();
        $dataset = $createDatasetAction->execute($validated, $project->id, $user->id);
        $assignDoiToDatasetAction($dataset, $user);
        return redirect(route('projects.datasets.edit', [$project, $dataset]));
    }
}
