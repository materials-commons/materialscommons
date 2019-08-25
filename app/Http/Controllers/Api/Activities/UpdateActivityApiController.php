<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\UpdateActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\UpdateActivityRequest;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class UpdateActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  UpdateActivityRequest  $request
     * @param  UpdateActivityAction  $updateActivityAction
     *
     * @param  Activity  $activity
     *
     * @return ActivityResource
     */
    public function __invoke(UpdateActivityRequest $request, UpdateActivityAction $updateActivityAction, Activity $activity)
    {
        $validated = $request->validated();
        $activity = $updateActivityAction($validated, $activity);
        return new ActivityResource($activity);
    }
}
