<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;
use function redirect;
use function route;

class StoreProjectWebController extends Controller
{
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $rv = $createProjectAction->execute($validated, auth()->id());
        if (is_null($rv['project'])) {
            flash("Unable to create project with name {$validated['name']}")->error();
            return redirect(route('dashboard.projects.show'));
        }
        $project = $rv['project'];
        auth()->user()->addToActiveProjects($project);
        if ($request->input('experiments-next', false)) {
            $showOverview = $request->input('show-overview', false);
            return redirect(route('projects.experiments.create', [$project, 'show-overview' => $showOverview]));
        }
        flash("created project")->success();
        return redirect(route('projects.show', [$project]));
    }
}
