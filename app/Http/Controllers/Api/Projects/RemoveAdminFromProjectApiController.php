<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Users\RemoveAdminFromProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;

class RemoveAdminFromProjectApiController extends Controller
{
    public function __invoke(RemoveAdminFromProjectAction $removeAdminFromProjectAction, $projectId, User $user)
    {
        $project = Project::with('rootDir')->findOrFail($projectId);

        $this->authorize('updateUsers', $project);
        return new ProjectResource($removeAdminFromProjectAction($project, $user));
    }
}
