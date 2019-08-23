<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\CreateActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\CreateActivityRequest;

class CreateActivityApiController extends Controller
{
    /**
     * Create an activity in a project
     *
     * @param  CreateActivityRequest  $request
     * @param  CreateActivityAction  $createActivityAction
     *
     * @return void
     */
    public function __invoke(CreateActivityRequest $request, CreateActivityAction $createActivityAction)
    {
        $validated = $request->validated();
        $activity = $createActivityAction($validated);
        return $activity;
    }
}
