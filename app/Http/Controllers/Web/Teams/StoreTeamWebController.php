<?php

namespace App\Http\Controllers\Web\Teams;

use App\Actions\Teams\CreateTeamAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamRequest;

class StoreTeamWebController extends Controller
{
    public function __invoke(CreateTeamRequest $request, CreateTeamAction $createTeamAction)
    {
        return redirect(route('teams.show', [
            'team' => $createTeamAction->execute($request->validated(), auth()->id()),
        ]));
    }
}
