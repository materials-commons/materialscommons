<?php

namespace App\Actions\Users;

use App\Models\Project;
use App\Models\User;

class AddUserToProjectAction
{
    public function __invoke(Project $project, User $userToAdd)
    {
        $team = $project->team;
        $team->members()->syncWithoutDetaching($userToAdd);
        return $project;
    }
}