<?php

namespace App\Http\Controllers\Api\Entities;

use App\Actions\Entities\CreateEntityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Entities\CreateEntityRequest;
use App\Http\Resources\Entities\EntityResource;

class CreateEntityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  CreateEntityRequest  $request
     * @param  CreateEntityAction  $createEntityAction
     * @return EntityResource
     */
    public function __invoke(CreateEntityRequest $request, CreateEntityAction $createEntityAction)
    {
        $validated = $request->validated();
        $entity = $createEntityAction($validated);
        return new EntityResource($entity);
    }
}
