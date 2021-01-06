<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Mail\UserAddedToProjectMail;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AddAdminToProjectWebController extends Controller
{
    public function __invoke(Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $team = $project->team;
        $team->admins()->syncWithoutDetaching($user);
        Mail::to($user)
            ->queue(new UserAddedToProjectMail($project, $user, auth()->user()));
        return redirect(route('projects.users.edit', [$project]));
    }
}
