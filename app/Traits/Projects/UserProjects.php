<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use function array_merge;
use function collect;
use function is_null;

trait UserProjects
{
    public function getUserArchivedProjects($userId, $teamIds = null)
    {
        if (is_null($teamIds)) {
            $teamIds = $this->getUserTeamIds($userId);
        }
        return Project::with('owner', 'rootDir', 'team.members', 'team.admins')
                      ->withCount('entities')
                      ->whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
                      ->whereNotNull('archived_at')
                      ->orderBy('name')
                      ->get();
    }

    public function getUserArchivedProjectsCount($userId, $teamIds = null): int
    {
        if (is_null($teamIds)) {
            $teamIds = $this->getUserTeamIds($userId);
        }
        return Project::whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
                      ->whereNotNull('archived_at')
                      ->count();
    }

    public function getUserProjects($userId, $teamIds = null)
    {
        if (is_null($teamIds)) {
            $teamIds = $this->getUserTeamIds($userId);
        }
        return Project::with('owner', 'rootDir', 'team.members', 'team.admins')
                      ->withCount('entities')
                      ->whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
            ->whereNull('archived_at')
                      ->orderBy('name')
                      ->get();
    }

    public function getUserProjectsCount($userId, $teamIds = null): int
    {
        if (is_null($teamIds)) {
            $teamIds = $this->getUserTeamIds($userId);
        }
        return Project::whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
                      ->whereNull('archived_at')
                      ->count();
    }

    public function getUserTeamIds($userId): array
    {
        $memberTeams = DB::table('team2member')
                         ->where('user_id', $userId)
                         ->select('team_id')
                         ->get()
                         ->pluck('team_id')
                         ->toArray();
        $adminTeams = DB::table('team2admin')
                        ->where('user_id', $userId)
                        ->select('team_id')
                        ->get()
                        ->pluck('team_id')
                        ->toArray();
        return collect(array_merge($memberTeams, $adminTeams))->unique()->toArray();
    }
}