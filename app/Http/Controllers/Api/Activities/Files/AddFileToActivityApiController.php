<?php

namespace App\Http\Controllers\Api\Activities\Files;

use App\Actions\Activities\Files\AddFilesToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activies\Files\AddFilesToActivityRequest;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Activity;

class AddFileToActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Activies\Files\AddFilesToActivityRequest  $request
     * @param  \App\Actions\Activities\Files\AddFilesToActivityAction  $addFilesToActivityAction
     * @param  \App\Models\Activity  $activity
     * @return \App\Http\Resources\Activities\ActivityResource
     */
    public function __invoke(
        AddFilesToActivityRequest $request,
        AddFilesToActivityAction $addFilesToActivityAction,
        Activity $activity
    )
    {
        $files = $request->input('files');
        $activity = $addFilesToActivityAction($activity, $files);
        return new ActivityResource($activity);
    }
}
