<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Actions\Users\AddUserToProjectAction;
use App\Http\Controllers\Controller;
use App\Mail\UserAddedToProjectMail;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AddUserToProjectWebController extends Controller
{
    public function __invoke(AddUserToProjectAction $addUserToProjectAction, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $project->load('team.members');
        $addUserToProjectAction($project, $user);
        Mail::to($user)
            ->queue(new UserAddedToProjectMail($project, $user, auth()->user()));
        flash("{$user->name} added as project member.")->success();
        return redirect(route('projects.users.edit', [$project]));
    }
}
