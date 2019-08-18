<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\UpdateActivityAction;
use App\Http\Requests\Activities\UpdateActivityRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Activities\UpdateActivityRequest  $request
     * @param  \App\Actions\Activities\UpdateActivityAction  $updateActivityAction
     *
     * @param $activityId
     *
     * @return void
     */
    public function __invoke(UpdateActivityRequest $request, UpdateActivityAction $updateActivityAction, $activityId)
    {
        $validated = $request->validated();
        return $updateActivityAction($activityId, $validated);
    }
}
