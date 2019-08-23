<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Projects\UpdateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;

class UpdateProjectApiController extends Controller
{
    /**
     * Update a project.
     *
     * @param  UpdateProjectRequest  $request
     * @param  UpdateProjectAction  $updateProjectAction
     * @param  Project  $project
     * @return ProjectResource
     */
    public function __invoke(UpdateProjectRequest $request, UpdateProjectAction $updateProjectAction, Project $project)
    {
        $validated = $request->validated();
        $project = $updateProjectAction($validated, $project);
        return new ProjectResource($project);
    }
}
