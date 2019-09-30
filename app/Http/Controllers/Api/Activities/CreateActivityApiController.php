<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\CreateActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\CreateActivityRequest;
use App\Http\Resources\Activities\ActivityResource;

class CreateActivityApiController extends Controller
{
    /**
     * Create an activity in a project.
     *
     * @param  CreateActivityRequest  $request
     * @param  CreateActivityAction  $createActivityAction
     *
     * @return ActivityResource
     */
    public function __invoke(CreateActivityRequest $request, CreateActivityAction $createActivityAction)
    {
        $validated = $request->validated();
        $userId = auth()->id();
        $activity = $createActivityAction($validated, $userId);
        return (new ActivityResource($activity))->response()->setStatusCode(201);
    }
}
