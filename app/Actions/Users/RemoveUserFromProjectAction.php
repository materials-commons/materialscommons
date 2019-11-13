<?php

namespace App\Actions\Users;

use App\Models\Project;
use App\Models\User;

class RemoveUserFromProjectAction
{
    public function __invoke(Project $project, User $userToRemove)
    {
        abort_if($project->owner->id === $userToRemove->id, 400, 'Owner cannot remove self');
        $project->users()->detach($userToRemove);
        return $project;
    }
}