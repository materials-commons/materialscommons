<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class RemoveAdminFromProjectWebController extends Controller
{
    public function __invoke(Request $request, Project $project, User $user)
    {
        $this->authorize('updateUsers', $project);
        abort_if($project->owner_id === $user->id, 400, 'Owner cannot remove self');
        $team = $project->team;
        $team->admins()->detach($user);
        return redirect(route('projects.users.edit', [$project]));
    }
}
