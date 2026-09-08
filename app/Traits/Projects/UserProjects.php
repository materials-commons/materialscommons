<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use function array_merge;
use function collect;
use function is_null;

trait UserProjects
{
    private $teamIds = null;
    private $projectsFromTeamIds = null;
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
        if (!is_null($this->projectsFromTeamIds)) {
            return $this->projectsFromTeamIds;
        }

        $this->projectsFromTeamIds = Project::with('owner', 'rootDir', 'team.members', 'team.admins')
            ->withCount(['samples', 'computations', 'publishedDatasets'])
                      ->whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
            ->whereNull('archived_at')
                      ->orderBy('name')
                      ->get();
        return $this->projectsFromTeamIds;
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
        if (!is_null($this->teamIds)) {
            return $this->teamIds;
        }

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
        $this->teamIds = collect(array_merge($memberTeams, $adminTeams))->unique()->toArray();
        return $this->teamIds;
    }
}
