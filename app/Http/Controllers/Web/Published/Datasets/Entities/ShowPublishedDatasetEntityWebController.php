<?php

namespace App\Http\Controllers\Web\Published\Datasets\Entities;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Entity;

class ShowPublishedDatasetEntityWebController extends Controller
{
    public function __invoke(Dataset $dataset, $entityId)
    {
        $entity = Entity::with('entityStates.attributes.values')->findOrFail($entityId);
        $attributes = collect();
        foreach ($entity->entityStates as $es) {
            foreach ($es->attributes as $attribute) {
                $attributes->push($attribute);
            }
        }
        return view('public.datasets.entities.show', compact('dataset', 'entity', 'attributes'));
    }
}
