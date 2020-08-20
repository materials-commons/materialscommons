<?php

namespace App\Policies;

use App\Models\Experiment;
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

        $project->load('team.admins');

        if ($this->userIsAdminForProject($user, $project)) {
            return true;
        }

        return $this->userIsAdminInTeamThatContainsProject($user->id, $project->id);
    }

    public function userIsAdminForProject(User $user, Project $project)
    {
        return $project->team->admins->contains(function ($admin) use ($user) {
            return $user->id === $admin->id;
        });
    }

    private function userIsAdminInTeamThatContainsProject($userId, $projectId)
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

    public function canDeleteExperiment(User $user, Project $project, Experiment $experiment)
    {
//        echo "in canDeleteExperiment {$user->id}/{$project->owner_id}/{$experiment->owner_id}\n";
        if ($experiment->owner_id == $user->id) {
            return true;
        }

        if ($project->owner_id == $user->id) {
            return true;
        }

        return false;
    }
}
