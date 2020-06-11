<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Users\AddUserToProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;

class AddUserToProjectApiController extends Controller
{
    public function __invoke(AddUserToProjectAction $addUserToProjectAction, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        return new ProjectResource($addUserToProjectAction($project, $user));
    }
}
