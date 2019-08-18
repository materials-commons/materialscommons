<?php

namespace App\Actions\Activities;

use App\Models\Activity;

class UpdateActivityAction
{
    public function __invoke($activityId, $attrs)
    {
        return tap(Activity::findOrFail($activityId))->update($attrs)->fresh();
    }
}