<?php

namespace App\Http\Controllers\Api\EntityStates;

use App\Actions\EntityStates\CreateEntityStateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntityStates\CreateEntityStateRequest;
use App\Http\Resources\Entities\EntityResource;
use App\Models\Activity;
use App\Models\Entity;

class CreateEntityStateForEntityApiController extends Controller
{
    public function __invoke(CreateEntityStateRequest $request, CreateEntityStateAction $createEntityStateAction,
        Entity $entity, Activity $activity)
    {
        $validated = $request->validated();
        $createEntityStateAction->execute($validated, $entity, $activity, auth()->id());
        $entity->refresh();
        return (new EntityResource($entity))->response()->setStatusCode(201);
    }
}
