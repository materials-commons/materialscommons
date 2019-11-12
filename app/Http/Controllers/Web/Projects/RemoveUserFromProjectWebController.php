<?php

namespace App\Http\Controllers\Web\Projects;

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
        return; // Add a view here
    }
}
