<?php

namespace App\Actions\Activities;

use App\Models\Activity;

class UpdateActivityAction
{
    public function __invoke($attrs, Activity $activity)
    {
        return tap($activity)->update($attrs)->fresh();
    }
}