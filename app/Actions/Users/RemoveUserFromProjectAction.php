<?php

namespace App\Actions\Users;

use App\Models\Project;
use App\Models\User;

class RemoveUserFromProjectAction
{
    public function __invoke(Project $project, User $userToRemove)
    {
        $project->users()->detach($userToRemove);
        return $project;
    }
}