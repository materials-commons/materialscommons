<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Users\RemoveUserFromProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;

class RemoveUserFromProjectApiController extends Controller
{
    public function __invoke(RemoveUserFromProjectAction $removeUserFromProjectAction, $projectId, User $user)
    {
        $project = Project::with('rootDir')->findOrFail($projectId);

        $this->authorize('updateUsers', $project);
        return new ProjectResource($removeUserFromProjectAction($project, $user));
    }
}
