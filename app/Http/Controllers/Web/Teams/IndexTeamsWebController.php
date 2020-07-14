<?php

namespace App\Http\Controllers\Web\Teams;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class IndexTeamsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.teams.index', ['user' => $this->getUserWithTeams()]);
    }

    private function getUserWithTeams()
    {
        return User::with(['adminTeams.members', 'adminTeams.admins', 'memberOfTeams.members', 'memberOfTeams.admins'])
                   ->firstWhere('id', auth()->id());
    }
}
