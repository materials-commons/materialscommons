<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class DeleteActivityAction
{
    /**
     * @param  Activity  $activity
     *
     * @throws \Exception
     */
    public function __invoke(Activity $activity)
    {
        DB::transaction(function () use ($activity) {
            $activity->attributes()->delete();
            $activity->delete();
        });
    }
}
