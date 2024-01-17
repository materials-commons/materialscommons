<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use function array_merge;
use function collect;

trait UserProjects
{
    public function getUserProjects($userId)
    {
        $teamIds = $this->getUserTeamIds($userId);
        return Project::with('owner', 'rootDir', 'team.members', 'team.admins')
                      ->withCount('entities')
                      ->whereIn('team_id', $teamIds)
                      ->whereNull('deleted_at')
                      ->orderBy('name')
                      ->get();
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