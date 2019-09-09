<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\CreateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\CreateExperimentRequest;
use App\Models\Project;

class StoreExperimentWebController extends Controller
{
    public function __invoke(
        CreateExperimentRequest $request,
        CreateExperimentAction $createExperimentAction,
        Project $project
    ) {
        $validated = $request->validated();
        $createExperimentAction($validated);
        return redirect(route('projects.show', compact('project')));
    }
}
