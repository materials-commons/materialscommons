<?php

namespace App\Http\Controllers\Api\Activities;

use App\Actions\Activities\CreateActivityAction;
use App\Http\Requests\Activities\CreateActivityRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Activities\CreateActivityRequest  $request
     * @param  \App\Actions\Activities\CreateActivityAction  $createActivityAction
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
