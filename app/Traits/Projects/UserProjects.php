<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use function array_merge;
use function collect;
use function is_null;

trait UserProjects
{
    public function getUserArchivedProjects($userId)
    {
        $teamIds = $this->getUserTeamIds($userId);
        return $this->getUserArchivedProjectsFromTeamIds($teamIds);
    }

    public function getUserArchivedProjectsFromTeamIds($teamIds)
    {
        return Project::with('owner', 'rootDir', 'team.members', 'team.admins')
            ->withCount(['samples', 'computations'])
                      ->whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
                      ->whereNotNull('archived_at')
                      ->orderBy('name')
                      ->get();
    }

    public function getUserArchivedProjectsCount($userId): int
    {
        $teamIds = $this->getUserTeamIds($userId);
        return $this->getUserArchivedProjectsCountFromTeamIds($teamIds);
    }

    public function getUserArchivedProjectsCountFromTeamIds($teamIds): int
    {
        return Project::whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
                      ->whereNotNull('archived_at')
                      ->count();
    }

    public function getUserProjects($userId)
    {
        $teamIds = $this->getUserTeamIds($userId);
        return $this->getUserProjectsFromTeamIds($teamIds);
    }

    public function getUserProjectsFromTeamIds($teamIds)
    {
        return Project::with('owner', 'rootDir', 'team.members', 'team.admins')
            ->withCount(['samples', 'computations'])
                      ->whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
            ->whereNull('archived_at')
                      ->orderBy('name')
                      ->get();
    }

    public function getUserProjectsCount($userId): int
    {
        $teamIds = $this->getUserTeamIds($userId);
        return $this->getUserProjectsCountFromTeamIds($teamIds);
    }

    public function getUserProjectsCountFromTeamIds($teamIds): int
    {
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