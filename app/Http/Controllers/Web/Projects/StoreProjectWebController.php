<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;

class StoreProjectWebController extends Controller
{
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $rv = $createProjectAction($validated);
        $showOverview = $request->input('show-overview', false);
        if ($request->input('experiments-next', false)) {
            $project = $rv['project'];
            return redirect(route('projects.experiments.create', [$project, 'show-overview' => $showOverview]));
        }
        return redirect(route('projects.index'));
    }

}
