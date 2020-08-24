<?php

namespace App\Actions\Datasets;

class UpdateDatasetEntitySelectionAction
{
    public function __invoke($entity, $dataset, $remove = false)
    {
        if (!$remove) {
            $this->addEntityToSelection($dataset, $entity);
        } else {
            $this->removeEntityFromSelection($dataset, $entity);
        }

//        $dataset->entities()->toggle($entity);
        return $dataset;
    }

    private function addEntityToSelection($dataset, $entity)
    {
        $dataset->update([
            'entity_selection' => collect($dataset->entity_selection)->merge($entity->name)->unique()->toArray(),
        ]);
    }

    private function removeEntityFromSelection($dataset, $entity)
    {
        $dataset->update([
            'entity_selection' => collect($dataset->entity_selection)->reject(function ($name) use ($entity) {
                return $entity->name === $name;
            }),
        ])->toArray();
    }
}