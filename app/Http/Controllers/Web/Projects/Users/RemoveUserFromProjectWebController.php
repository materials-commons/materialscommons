<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Actions\Users\RemoveUserFromProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class RemoveUserFromProjectWebController extends Controller
{
    public function __invoke(RemoveUserFromProjectAction $removeUserFromProjectAction, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $removeUserFromProjectAction($project, $user);
        return redirect(route('projects.users.edit', [$project]));
    }
}
