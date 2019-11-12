<?php

namespace App\Actions\Users;

use App\Models\Project;

class RemoveUserFromProjectAction
{
    public function __invoke(Project $project, $requestingUser, $userIdToAdd)
    {
        if ($project->user->id !== $requestingUser->id) {
            return false;
        }
    }
}