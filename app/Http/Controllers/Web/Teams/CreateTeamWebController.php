<?php

namespace App\Http\Controllers\Web\Teams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateTeamWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.teams.create');
    }
}
