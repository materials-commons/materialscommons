<?php

namespace App\Actions\Entities;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Traits\EntityStates\AddEntityStateAttributes;

class CreateEntityAction
{
    use AddEntityStateAttributes;

    public function __invoke($data, $userId)
    {
        $entitiesData = collect($data)->except('experiment_id')->toArray();
        $entitiesData['owner_id'] = $userId;
        $entity = Entity::create($entitiesData);
        $entityState = EntityState::create([
            'owner_id'  => $userId,
            'entity_id' => $entity->id,
            'current'   => true,
        ]);

        if (array_key_exists('experiment_id', $data)) {
            $entity->experiments()->attach($data['experiment_id']);
        }

        if (array_key_exists('activity_id', $data)) {
            $activity = Activity::findOrFail($data['activity_id']);
            $activity->entities()->attach($entity);
            $activity->entityStates()->attach([$entityState->id => ['direction' => 'out']]);
        }

        $entity->refresh();

        if (!array_key_exists('attributes', $data)) {
            return $entity;
        }

        $this->addSampleAttributes($entityState, $data['attributes']);

        return $entity;
    }
}