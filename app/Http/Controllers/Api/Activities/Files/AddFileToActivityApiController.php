<?php

namespace App\Http\Controllers\Api\Activities\Files;

use App\Actions\Activities\Files\AddFileToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class AddFileToActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Activities\Files\AddFileToActivityAction  $addFileToActivityAction
     * @param  \App\Models\Activity  $activity
     * @param $fileId
     * @return \App\Http\Resources\Activities\ActivityResource
     */
    public function __invoke(AddFileToActivityAction $addFileToActivityAction, Activity $activity, $fileId)
    {
        $activity = $addFileToActivityAction($activity, $fileId);
        return new ActivityResource($activity);
    }
}
