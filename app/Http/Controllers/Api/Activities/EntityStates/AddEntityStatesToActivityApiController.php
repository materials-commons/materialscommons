<?php

namespace App\Http\Controllers\Api\Activities\EntityStates;

use App\Actions\Activities\EntityStates\AddEntityStatesToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\EntityStates\AddEntityStatesToActivityRequest;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class AddEntityStatesToActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Activities\EntityStates\AddEntityStatesToActivityRequest  $request
     * @param  \App\Actions\Activities\EntityStates\AddEntityStatesToActivityAction  $addEntityStatesToActivityAction
     * @param  \App\Models\Activity  $activity
     * @return \App\Http\Resources\Activities\ActivityResource
     */
    public function __invoke(
        AddEntityStatesToActivityRequest $request,
        AddEntityStatesToActivityAction $addEntityStatesToActivityAction,
        Activity $activity
    )
    {
        $entityStates = $request->input('entity_states');
        $actitity     = $addEntityStatesToActivityAction($activity, $entityStates);
        return new ActivityResource($activity);
    }
}
