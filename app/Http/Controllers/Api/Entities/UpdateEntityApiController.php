<?php

namespace App\Http\Controllers\Api\Entities;

use App\Actions\Entities\UpdateEntityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Entities\UpdateEntityRequest;
use App\Http\Resources\Entities\EntityResource;
use App\Models\Entity;

class UpdateEntityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  UpdateEntityRequest  $request
     * @param  UpdateEntityAction  $updateEntityAction
     * @param  Entity  $entity
     * @return EntityResource
     */
    public function __invoke(UpdateEntityRequest $request, UpdateEntityAction $updateEntityAction, Entity $entity)
    {
        $validated = $request->validated();
        $entity = $updateEntityAction($validated, $entity);
        return new EntityResource($entity);
    }
}
