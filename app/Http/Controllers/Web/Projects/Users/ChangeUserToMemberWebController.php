<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ChangeUserToMemberWebController extends Controller
{
    public function __invoke(Request $request, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        abort_if($project->owner_id === $user->id, 400, 'Owner cannot remove self');
        $project->load(['team.admins', 'team.members']);
        $project->team->admins()->detach($user);
        $project->team->members()->syncWithoutDetaching($user);
        return redirect(route('projects.users.index', [$project]));
    }
}
