<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Actions\Users\AddUserToProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class AddUserToProjectWebController extends Controller
{
    public function __invoke(AddUserToProjectAction $addUserToProjectAction, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $addUserToProjectAction($project, $user);
        return redirect(route('projects.users.edit', [$project]));
    }
}
