<?php

namespace App\Actions\Entities;

use App\Models\Entity;

class UpdateEntityAction
{
    public function __invoke($attrs, Entity $entity)
    {
        return tap($entity)->update($entity)->fresh();
    }
}
