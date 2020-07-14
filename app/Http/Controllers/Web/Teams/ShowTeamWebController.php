<?php

namespace App\Http\Controllers\Web\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;

class ShowTeamWebController extends Controller
{
    public function __invoke($teamId)
    {
        return view('app.teams.show', [
            'team' => Team::with(['members', 'admins', 'projects', 'owner'])->findOrFail($teamId),
        ]);
    }
}
