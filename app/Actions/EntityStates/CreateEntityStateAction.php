<?php

namespace App\Actions\EntityStates;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Traits\EntityStates\AddEntityStateAttributes;
use Illuminate\Support\Facades\DB;

class CreateEntityStateAction
{
    use AddEntityStateAttributes;

    private EntityState $entityState;

    public function execute($data, Entity $entity, Activity $activity, $userId): EntityState
    {
        DB::transaction(function () use ($data, $entity, $activity, $userId) {
            $current = true;
            if (array_key_exists('current', $data)) {
                $current = $data['current'];
            }

            $this->entityState = EntityState::create([
                'owner_id'  => $userId,
                'entity_id' => $entity->id,
                'current'   => $current,
            ]);

            $activity->entityStates()->syncWithoutDetaching([$this->entityState->id => ['direction' => 'out']]);
            $activity->entities()->syncWithoutDetaching($entity);

            if (array_key_exists('attributes', $data)) {
                $this->addSampleAttributes($this->entityState, $data['attributes']);
            }
        });

        return $this->entityState;
    }
}
