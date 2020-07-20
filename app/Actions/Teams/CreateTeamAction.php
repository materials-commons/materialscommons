<?php

namespace App\Actions\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\DB;

class CreateTeamAction
{
    public function execute($data, $ownerId)
    {
        $team = new Team($data);
        $team->owner_id = $ownerId;
        DB::transaction(function () use ($team) {
            $team->save();
        });

        return $team;
    }
}