<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Users\AddUserToProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\AddUserToProjectRequest;
use App\Models\Project;
use App\Models\User;

class AddUserToProjectWebController extends Controller
{
    public function __invoke(AddUserToProjectAction $addUserToProjectAction, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $addUserToProjectAction($project, $user);
        return; // Add a view here...
    }
}
