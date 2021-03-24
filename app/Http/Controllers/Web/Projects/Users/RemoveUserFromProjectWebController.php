<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Actions\Users\RemoveUserFromProjectAction;
use App\Http\Controllers\Controller;
use App\Mail\UserRemovedFromProjectMail;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RemoveUserFromProjectWebController extends Controller
{
    public function __invoke(RemoveUserFromProjectAction $removeUserFromProjectAction, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        $removeUserFromProjectAction($project, $user);
        Mail::to($user)
            ->queue(new UserRemovedFromProjectMail($project, $user, auth()->user()));
        return redirect(route('projects.users.index', [$project]));
    }
}
