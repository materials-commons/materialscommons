<?php

namespace App\Http\Controllers\Api\Entities;

use App\Actions\Entities\DeleteEntityAction;
use App\Models\Entity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteEntityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  DeleteEntityAction  $deleteEntityAction
     * @param  Entity  $entity
     * @return void
     */
    public function __invoke(DeleteEntityAction $deleteEntityAction, Entity $entity)
    {
        $deleteEntityAction($entity);
    }
}
