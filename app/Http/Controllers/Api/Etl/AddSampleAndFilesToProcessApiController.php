<?php

namespace App\Http\Controllers\Api\Etl;

use App\Actions\Etl\AddEntityAndFilesToActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Etl\AddSampleAndFilesToProcessRequest;

class AddSampleAndFilesToProcessApiController extends Controller
{
    public function __invoke(
        AddSampleAndFilesToProcessRequest $request,
        AddEntityAndFilesToActivityAction $addEntityAndFilesToActivityAction
    ) {
        $validated = $request->validated();
        $entity = $addEntityAndFilesToActivityAction($validated);
        return $entity;
    }
}