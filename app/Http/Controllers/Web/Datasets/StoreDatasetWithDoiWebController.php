<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\AssignDoiToDatasetAction;
use App\Actions\Datasets\CreateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Project;

class StoreDatasetWithDoiWebController extends Controller
{
    public function __invoke(DatasetRequest $request, Project $project)
    {
        $createDatasetAction = new CreateDatasetAction(auth()->id());
        $validated = $request->validated();
        $action = $validated["action"];
        unset($validated["action"]);
        $dataset = $createDatasetAction($validated);
        $assignDoiToDatasetAction = new AssignDoiToDatasetAction();
        $user = auth()->user();
        $assignDoiToDatasetAction($dataset, $user);
        return redirect(route('projects.datasets.edit', [$project, $dataset]));
    }
}
