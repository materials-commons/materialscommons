<?php

namespace App\Http\Controllers\Api\Etl;

use App\Actions\Etl\AddMeasurementsToEntity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Etl\AddMeasurementsToSampleInProcessRequest;

class AddMeasurementsToSampleInProcessApiController extends Controller
{
    public function __invoke(AddMeasurementsToSampleInProcessRequest $request, AddMeasurementsToEntity $addMeasurementsToEntity)
    {
        $validated = $request->validated();
        $entity = $addMeasurementsToEntity($validated);

        return $entity;
    }
}
