<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class AddAdminToProjectWebController extends Controller
{
    public function __invoke(Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $team = $project->team;
        $team->admins()->syncWithoutDetaching($user);
        return redirect(route('projects.users.edit', [$project]));
    }
}