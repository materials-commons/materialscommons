<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Users\AddAdminToProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;

class AddAdminToProjectApiController extends Controller
{
    public function __invoke(AddAdminToProjectAction $addAdminToProjectAction, $projectId, User $user)
    {
        $project = Project::with('rootDir')->findOrFail($projectId);

        $this->authorize('updateUsers', $project);
        return new ProjectResource($addAdminToProjectAction($project, $user));
    }
}
