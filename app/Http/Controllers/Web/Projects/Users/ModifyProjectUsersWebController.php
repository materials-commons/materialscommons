<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ModifyProjectUsersWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $this->authorize('updateUsers', $project);
        $project->load('team.admins', 'team.members');
        return view('app.projects.users.edit', [
            'project' => $project,
            'users'   => $this->getUsersNotInProject($project),
        ]);
    }

    private function getUsersNotInProject(Project $project)
    {
        $userIdsInProject = $project->team->admins->pluck('id')
                                                  ->merge($project->team->members->pluck('id'))
                                                  ->toArray();
        return DB::table('users')
                 ->select('*')
                 ->whereNotIn('id', $userIdsInProject)
                 ->get();
    }
}
