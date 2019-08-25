<?php

namespace App\Actions\Entities;

use App\Models\Entity;

class DeleteEntityAction
{
    public function __invoke(Entity $entity)
    {
        $entity->delete();
    }
}
