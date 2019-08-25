<?php

namespace App\Actions\Activities;

use App\Models\Activity;

class DeleteActivityAction
{
    /**
     * @param  Activity  $activity
     *
     * @throws \Exception
     */
    public function __invoke(Activity $activity)
    {
        $activity->delete();
    }
}
