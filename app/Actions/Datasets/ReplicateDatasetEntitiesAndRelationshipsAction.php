<?php

namespace App\Actions\Datasets;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\EntityState;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class ReplicateDatasetEntitiesAndRelationshipsAction
{
    public function execute(Dataset $dataset)
    {
        $this->replicateEntitiesAndRelatedItems($dataset);
    }

    private function replicateEntitiesAndRelatedItems(Dataset $dataset)
    {
        $dataset->entitiesFromTemplate()->each(function (Entity $entity) use ($dataset) {
            $entity->load('entityStates.attributes.values', 'activities.attributes.values');
            $e = $entity->replicate()->fill([
                'uuid'      => $this->uuid(),
                'copied_at' => Carbon::now(),
                'copied_id' => $entity->id,
            ]);
            $e->save();
            $dataset->entities()->attach($e);
            $this->replicateEntityStatesAndRelationshipsForEntity($entity, $e);
            $this->replicateActivitiesAndRelationshipsForEntity($entity, $e);
        });
    }

    private function replicateEntityStatesAndRelationshipsForEntity(Entity $entity, Entity $e)
    {
        $entity->entityStates->each(function (EntityState $entityState) use ($e) {
            $es = $entityState->replicate()->fill([
                'uuid'      => $this->uuid(),
                'entity_id' => $e->id,
            ]);
            $es->save();
            $entityState->attributes->each(function (Attribute $attribute) use ($es) {
                $a = $attribute->replicate()->fill([
                    'uuid'            => $this->uuid(),
                    'attributable_id' => $es->id,
                ]);
                $a->save();
                $a->values->each(function (AttributeValue $attributeValue) use ($a) {
                    $av = $attributeValue->replicate()->fill([
                        'uuid'         => $this->uuid(),
                        'attribute_id' => $a->id,
                    ]);
                    $av->save();
                });
            });
        });
    }

    private function replicateActivitiesAndRelationshipsForEntity(Entity $entity, Entity $e)
    {
        $entity->activities->each(function (Activity $activity) use ($e) {
            $newActivity = $activity->replicate()->fill([
                'uuid'      => $this->uuid(),
                'copied_at' => Carbon::now(),
                'copied_id' => $activity->id,
            ]);
            $newActivity->save();
            $e->activities()->attach($newActivity);
            $activity->attributes->each(function (Attribute $attribute) use ($newActivity) {
                $a = $attribute->replicate()->fill([
                    'uuid'            => $this->uuid(),
                    'attributable_id' => $newActivity->id,
                ]);
                $a->save();
                $a->values->each(function (AttributeValue $attributeValue) use ($a) {
                    $av = $attributeValue->replicate()->fill([
                        'uuid'         => $this->uuid(),
                        'attribute_id' => $a->id,
                    ]);
                    $av->save();
                });
            });
        });
    }

    private function uuid()
    {
        return Uuid::uuid4()->toString();
    }
}