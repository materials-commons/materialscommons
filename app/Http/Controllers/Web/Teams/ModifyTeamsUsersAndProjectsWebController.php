<?php

namespace App\Http\Controllers\Web\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ModifyTeamsUsersAndProjectsWebController extends Controller
{
    public function __invoke(Request $request, $teamId)
    {
        return view('app.teams.modify-users-projects', [
            'team'  => Team::with(['members', 'admins', 'projects', 'owner'])->findOrFail($teamId),
            'users' => User::all(),
        ]);
    }
}
