<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Resources\Projects\ProjectResource;

/**
 * @group Projects
 *
 */
class CreateProjectApiController extends Controller
{
    /**
     * CreateProject
     *
     * Create a new project for the given user.
     * @bodyParam name string required The name of the project - must be unique for the user
     * @bodyParam description string A description of the project
     * @bodyParam is_active bool Whether this is an active project (default is true)
     *
     * @param  CreateProjectRequest  $request
     * @param  CreateProjectAction  $createProjectAction
     * @return ProjectResource
     */
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $project = $createProjectAction($validated);
        return (new ProjectResource($project))->response()->setStatusCode(201);
    }
}
