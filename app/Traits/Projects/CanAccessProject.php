<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use function auth;

// CanAccessProject provides methods for checking if user can access a project.
trait CanAccessProject
{
    // Returns true if user is either a member or an admin for the project.
    private function userCanAccessProject($userId, Project $project): bool
    {
        if ($project->owner_id == $userId) {
            return true;
        }

        if ($this->userIsProjectMember($project->id, $userId)) {
            return true;
        }

        return $this->userIsProjectAdmin($project->id, $userId);
    }

    private function userIsProjectMember($projectId, $userId): bool
    {
        $count = DB::table('team2member')
                   ->whereIn('team_id', function ($q) use ($projectId) {
                       $q->select('team_id')->from('projects')->where('id', $projectId);
                   })
                   ->where('user_id', $userId)
                   ->count();
        return $count != 0;
    }

    private function userIsProjectAdmin($projectId, $userId): bool
    {
        $count = DB::table('team2admin')
                   ->whereIn('team_id', function ($q) use ($projectId) {
                       $q->select('team_id')->from('projects')->where('id', $projectId);
                   })
                   ->where('user_id', $userId)
                   ->count();
        return $count != 0;
    }
}