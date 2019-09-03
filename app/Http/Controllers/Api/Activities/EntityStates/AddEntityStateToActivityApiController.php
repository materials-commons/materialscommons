<?php

namespace App\Http\Controllers\Api\Activities\EntityStates;

use App\Actions\Activities\EntityStates\AddEntityStatesToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class AddEntityStateToActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Activities\EntityStates\AddEntityStatesToActivityAction  $addEntityStatesToActivityAction
     * @param  \App\Models\Activity  $activity
     * @param $entityStateId
     * @return \App\Http\Resources\Activities\ActivityResource
     */
    public function __invoke(
        AddEntityStatesToActivityAction $addEntityStatesToActivityAction,
        Activity $activity,
        $entityStateId
    )
    {
        $activity = $addEntityStatesToActivityAction($activity, [$entityStateId]);
        return new ActivityResource($activity);
    }
}
