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
     * Create a new project for the given user. If user already has a project with that name
     * return it instead.
     *
     * @bodyParam name string required The name of the project - must be unique for the user
     * @bodyParam description string A description of the project
     * @bodyParam is_active bool Whether this is an active project (default is true)
     *
     * @param  CreateProjectRequest  $request
     * @param  CreateProjectAction  $createProjectAction
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $data = $createProjectAction->execute($validated, auth()->id());
        $project = $data['project'];
        $statusCode = $data['created'] ? 201 : 200;
        $project->load(['team.admins', 'team.members']);
        return (new ProjectResource($project))->response()->setStatusCode($statusCode);
    }
}
