<?php

namespace App\Http\Controllers\Api\Activities\Files;

use App\Actions\Activities\Files\AddFilesToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class AddFileToActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Activities\Files\AddFilesToActivityAction  $addFilesToActivityAction
     * @param  \App\Models\Activity  $activity
     * @param $fileId
     * @return \App\Http\Resources\Activities\ActivityResource
     */
    public function __invoke(AddFilesToActivityAction $addFilesToActivityAction, Activity $activity, $fileId)
    {
        $activity = $addFilesToActivityAction($activity, [$fileId]);
        return new ActivityResource($activity);
    }
}
