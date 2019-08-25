<?php

namespace App\Actions\Entities;

use App\Models\Entity;

class CreateEntityAction
{
    public function __invoke($data)
    {
        $data['owner_id'] = auth()->id();
        $entity = Entity::create($data);
        return $entity;
    }
}
