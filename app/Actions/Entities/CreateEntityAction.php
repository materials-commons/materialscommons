<?php

namespace App\Actions\Entities;

use App\Models\Entity;
use App\Models\EntityState;

class CreateEntityAction
{
    public function __invoke($data)
    {
        $entitiesData = collect($data)->except('experiment_id')->toArray();
        $entitiesData['owner_id'] = auth()->id();
        $entity = Entity::create($entitiesData);
        EntityState::create([
            'owner_id'  => auth()->id(),
            'entity_id' => $entity->id,
            'current'   => true,
        ]);

        if (array_key_exists('experiment_id', $data)) {
            $entity->experiments()->attach($data['experiment_id']);
        }
        return $entity->fresh();
    }
}
