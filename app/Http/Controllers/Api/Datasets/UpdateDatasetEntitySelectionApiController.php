<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\UpdateDatasetEntitySelectionRequest;
use App\Models\Dataset;
use App\Models\Entity;

class UpdateDatasetEntitySelectionApiController extends Controller
{
    public function __invoke(UpdateDatasetEntitySelectionRequest $request,
        UpdateDatasetEntitySelectionAction $updateDatasetEntitySelectionAction, Dataset $dataset)
    {
        $validated = $request->validated();
        $entity = Entity::findOrFail($validated["entity_id"]);
        $dataset = $updateDatasetEntitySelectionAction->update($entity, $dataset);
        $dataset->globus_acl_id = null;
        return $dataset;
    }
}
