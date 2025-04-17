<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthService
{
    // Check if user can access project. If user is_admin flag is set then return true,
    // otherwise check if user is a member or admin of project.
    public static function userCanAccessProject(User $user, Project $project): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if (self::userIsProjectMember($user, $project)) {
            return true;
        }

        return self::userIsProjectAdmin($user, $project);
    }

    // Check if user can access project by its id. If user is_admin flag is set then return true,
    // otherwise check if user is a member or admin of project.
    public static function userCanAccessProjectId(User $user, $projectId): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if (self::userIsProjectMemberByIds($user->id, $projectId)) {
            return true;
        }

        return self::userIsProjectAdminByIds($user->id, $projectId);
    }

    public static function userIdCanAccessProjectId($userId, $projectId): bool
    {
        $user = User::find($userId);
        return self::userCanAccessProjectId($user, $projectId);
    }

    // Check if user is a member of the project.
    public static function userIsProjectMember(User $user, Project $project): bool
    {
        return self::userIsProjectMemberByIds($user->id, $project->id);
    }

    // Check if user is a member of the project by ids.
    public static function userIsProjectMemberByIds($userId, $projectId): bool
    {
        $count = DB::table('team2member')
                   ->whereIn('team_id', function ($q) use ($projectId) {
                       $q->select('team_id')->from('projects')->where('id', $projectId);
                   })
                   ->where('user_id', $userId)
                   ->count();
        return $count != 0;
    }

    // Check if user is an admin in project.
    public static function userIsProjectAdmin(User $user, Project $project): bool
    {
        return self::userIsProjectAdminByIds($user->id, $project->id);
    }

    // Check if user is an admin project by ids.
    public static function userIsProjectAdminByIds($userId, $projectId): bool
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
