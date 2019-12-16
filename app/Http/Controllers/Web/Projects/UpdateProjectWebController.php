<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\UpdateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Models\Project;

class UpdateProjectWebController extends Controller
{
    public function __invoke(UpdateProjectRequest $request, UpdateProjectAction $updateProjectAction, Project $project)
    {
        $validated = $request->validated();
        $updateProjectAction($validated, $project);

        return redirect(route('projects.show', [$project]));
    }
}
