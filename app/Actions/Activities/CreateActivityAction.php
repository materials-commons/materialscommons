<?php

namespace App\Actions\Activities;

use App\Models\Activity;

class CreateActivityAction
{
    public function __invoke($data)
    {
        $activitiesData = collect($data)->except('experiment_id')->toArray();
        $activitiesData['owner_id'] = auth()->id();
        $activity = Activity::create($activitiesData);
        if (array_key_exists('experiment_id', $data)) {
            $activity->experiments()->attach($data['experiment_id']);
        }
        return $activity->fresh();
    }
}