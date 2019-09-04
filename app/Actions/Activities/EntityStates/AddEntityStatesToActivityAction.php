<?php

namespace App\Actions\Activities\EntityStates;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class AddEntityStatesToActivityAction
{
    public function __invoke(Activity $activity, $entityStates)
    {
        $entries = collect();
        foreach ($entityStates as $entityState) {
            $entries->put($entityState["id"], ["direction" => $entityState["direction"]]);
        }

        DB::transaction(function () use ($activity, $entityStates) {
            $activity->entityStates()->attach($entityStates);
        });

        return $activity;
    }
}