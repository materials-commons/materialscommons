<?php

namespace App\Http\Queries\Activities;

use App\Models\Activity;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleActivityQuery extends ActivitiesQueryBuilder
{
    use GetRequestParameterId;

    /**
     * Build query for activity.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function __construct(?Request $request = null)
    {
        $activityId = $this->getParameterId('activity');
        $query = Activity::where('id', $activityId);
        parent::__construct($query, $request);
    }
}