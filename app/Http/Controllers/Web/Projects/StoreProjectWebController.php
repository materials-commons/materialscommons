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
        $rv = $createProjectAction->execute($validated, auth()->id());
        $project = $rv['project'];
        auth()->user()->addToActiveProjects($project);
        if ($request->input('experiments-next', false)) {
            $showOverview = $request->input('show-overview', false);
            return redirect(route('projects.experiments.create', [$project, 'show-overview' => $showOverview]));
        }
        return redirect(route('projects.show', [$project]));
    }
}
