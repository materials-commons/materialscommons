<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Resources\Projects\ProjectResource;

class CreateProjectApiController extends Controller
{
    /**
     * Create a new project for the given user.
     *
     * @param  CreateProjectRequest  $request
     * @param  CreateProjectAction  $createProjectAction
     * @return ProjectResource
     */
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $project = $createProjectAction($validated);
        return new ProjectResource($project);
    }
}
