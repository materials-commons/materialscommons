<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function updateUsers(User $user, Project $project)
    {
        if ($project->owner_id === $user->id) {
            return true;
        }

        return $this->userIsProjectTeamAdmin($user->id, $project->id);
    }

    private function userIsProjectTeamAdmin($userId, $projectId)
    {
        $count = DB::table('team2admin')
                   ->where('user_id', $userId)
                   ->whereIn('team_id',
                       DB::table('teams')
                         ->select('id')
                         ->where('project_id', $projectId)
                   )->count();
        return $count !== 0;
    }
}
