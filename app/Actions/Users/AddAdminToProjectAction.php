<?php

namespace App\Actions\Users;

use App\Models\Project;
use App\Models\User;

class AddAdminToProjectAction
{
    public function __invoke(Project $project, User $userToAdd)
    {
        $team = $project->team;
        $team->admins()->syncWithoutDetaching($userToAdd);
        return $project->fresh(['team.admins', 'team.members']);
    }
}