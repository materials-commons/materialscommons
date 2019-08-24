<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\DeleteActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Activity;

class DeleteActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Activities\DeleteActivityAction  $deleteActivityAction
     * @param  \App\Models\Activity  $activity
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(DeleteActivityAction $deleteActivityAction, Activity $activity)
    {
        $deleteActivityAction($activity);
    }
}
