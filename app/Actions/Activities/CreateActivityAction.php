<?php

namespace App\Actions\Activities;

use App\Models\Activity;

class CreateActivityAction
{
    public function __invoke($data)
    {
        $data['owner_id'] = auth()->id();
        $activity = Activity::create($data);
        return $activity;
    }
}