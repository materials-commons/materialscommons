<?php

namespace App\Http\Controllers\Api\Entities;

use App\Actions\Entities\DeleteEntityAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;

class DeleteEntityApiController extends Controller
{
    public function __invoke(DeleteEntityAction $deleteEntityAction, $projectId, Entity $entity)
    {
        $deleteEntityAction($entity);
    }
}
