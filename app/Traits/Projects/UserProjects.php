<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

trait UserProjects
{
    public function getUserProjects($userId)
    {
        $memberTeams = DB::table('team2member')
                         ->where('user_id', 130)
                         ->select('team_id')
                         ->get()
                         ->pluck('team_id')
                         ->toArray();
        $adminTeams = DB::table('team2admin')
                        ->where('user_id', 130)
                        ->select('team_id')
                        ->get()
                        ->pluck('team_id')
                        ->toArray();
        $allTeams = collect(array_merge($memberTeams, $adminTeams))->unique()->toArray();
        return Project::with('owner', 'rootDir')->whereIn('team_id', $allTeams)->get();
    }
}