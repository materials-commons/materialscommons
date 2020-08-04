<?php

namespace App\Actions\Users;

use App\Models\Project;
use App\Models\User;

class RemoveUserFromProjectAction
{
    public function __invoke(Project $project, User $user)
    {
        abort_if($project->owner->id === $user->id, 400, 'Owner cannot remove self');
        $team = $project->team;
        $team->members()->detach($user);
        return $project;
    }
}