<?php

namespace App\Http\Controllers\Api\Activities\EntityStates;

use App\Actions\Activities\EntityStates\AddEntityStatesToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\EntityStates\AddEntityStatesToActivityRequest;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class AddEntityStatesToActivityApiController extends Controller
{
    public function __invoke(AddEntityStatesToActivityRequest $request,
                             AddEntityStatesToActivityAction  $addEntityStatesToActivityAction, Activity $activity)
    {
        $entityStates = $request->input('entity_states');
        $activity = $addEntityStatesToActivityAction($activity, $entityStates);
        return new ActivityResource($activity);
    }
}
