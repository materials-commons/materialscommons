<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\DeleteActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Activity;

class DeleteActivityApiController extends Controller
{
    public function __invoke(DeleteActivityAction $deleteActivityAction, $projectId, Activity $activity)
    {
        $deleteActivityAction($activity);
    }
}
