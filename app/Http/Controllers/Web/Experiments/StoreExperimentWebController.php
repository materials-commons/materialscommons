<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\CreateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\CreateExperimentRequest;
use App\Models\Project;

class StoreExperimentWebController extends Controller
{
    public function __invoke(CreateExperimentRequest $request, CreateExperimentAction $createExperimentAction,
                             Project                 $project)
    {
        $validated = $request->validated();
        $experiment = $createExperimentAction($validated);
        if ($request->input('files-next', false)) {
            $showOverview = $request->input('show-overview', false);
            return redirect(route('projects.folders.index', [$project, 'show-overview' => $showOverview]));
        }
        return redirect(route('projects.experiments.show', [$project, $experiment]));
    }
}
