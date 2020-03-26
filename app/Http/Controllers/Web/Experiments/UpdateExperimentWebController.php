<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\UpdateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\UpdateExperimentRequest;
use App\Models\Experiment;
use App\Models\Project;

class UpdateExperimentWebController extends Controller
{
    public function __invoke(UpdateExperimentRequest $request, UpdateExperimentAction $updateExperimentAction,
        Project $project, Experiment $experiment)
    {
        $validated = $request->validated();
        $updateExperimentAction($validated, $experiment);
        return redirect(route('projects.show', compact('project')));
    }
}
