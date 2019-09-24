<?php

namespace App\Actions\Entities;

use App\Models\Entity;

class CreateEntityAction
{
    public function __invoke($data)
    {
        $entitiesData = collect($data)->except('experiment_id')->toArray();
        $entitiesData['owner_id'] = auth()->id();
        $entity = Entity::create($entitiesData);
        if (array_key_exists('experiment_id', $data)) {
            $entity->experiments()->attach($data['experiment_id']);
        }
        return $entity->fresh();
    }
}
