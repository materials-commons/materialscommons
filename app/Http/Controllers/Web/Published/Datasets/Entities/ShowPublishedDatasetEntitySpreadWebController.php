<?php

namespace App\Http\Controllers\Web\Published\Datasets\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;

class ShowPublishedDatasetEntitySpreadWebController extends Controller
{
    public function __invoke(Dataset $dataset, $entityId)
    {
        $entity = Entity::with('activities')->findOrFail($entityId);
        $activityIds = $entity->activities->pluck('id')->toArray();
        $activities = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files'])
                              ->whereIn('id', $activityIds)
                              ->orderBy('name')
                              ->get();
        return view('public.datasets.entities.show-spread', [
            'dataset'    => $dataset,
            'entity'     => $entity,
            'activities' => $activities,
        ]);
    }
}
