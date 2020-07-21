<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class RemoveAdminFromProjectWebController extends Controller
{
    public function __invoke(Request $request, Project $project, User $userToRemove)
    {
        $this->authorize('updateUsers', $project);
        abort_if($project->owner->id === $userToRemove->id, 400, 'Owner cannot remove self');
        $team = Team::firstWhere('project_id', $project->id);
        $team->admins()->detach($userToRemove);
        return redirect(route('projects.users.edit', [$project]));
    }
}
