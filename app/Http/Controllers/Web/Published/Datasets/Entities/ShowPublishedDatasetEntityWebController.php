<?php

namespace App\Http\Controllers\Web\Published\Datasets\Entities;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Entity;
use App\Traits\GroupByActivityType;

class ShowPublishedDatasetEntityWebController extends Controller
{
    use GroupByActivityType;

    public function __invoke(Dataset $dataset, $entityId)
    {
        $entity = Entity::with('activities')->findOrFail($entityId);
        $activityIds = $entity->activities->pluck('id')->toArray();

        return view('public.datasets.entities.show-grouped', [
            'dataset'                    => $dataset,
            'entity'                     => $entity,
            'activityTypes'              => $this->getActivityTypes($activityIds),
            'attributesByActivityType'   => $this->getAttributesByActivityType($activityIds),
            'filesByActivityType'        => $this->getFilesByActivityType($activityIds),
            'measurementsByActivityType' => $this->getMeasurementsByActivityType($activityIds),
        ]);
    }
}
