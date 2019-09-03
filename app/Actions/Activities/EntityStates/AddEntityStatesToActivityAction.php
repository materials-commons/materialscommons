<?php

namespace App\Actions\Activities\EntityStates;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class AddEntityStatesToActivityAction
{
    public function __invoke(Activity $activity, $entityStates)
    {
        DB::transaction(function () use ($activity, $entityStates) {
            $activity->entityStates()->attach($entityStates);
        });

        return $activity;
    }
}