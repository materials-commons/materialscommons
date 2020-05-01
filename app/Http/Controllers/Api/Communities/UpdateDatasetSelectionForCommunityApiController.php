<?php

namespace App\Http\Controllers\Api\Communities;

use App\Actions\Communities\UpdateCommunityDatasetSelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communities\UpdateCommunityDatasetRequest;

class UpdateDatasetSelectionForCommunityApiController extends Controller
{
    public function __invoke(UpdateCommunityDatasetRequest $request,
        UpdateCommunityDatasetSelectionAction $updateCommunityDatasetSelectionAction)
    {
        $validated = $request->validated();
        $user = auth()->user();
        return $updateCommunityDatasetSelectionAction->execute($validated['community_id'], $validated['dataset_id'],
            $user->id);
    }
}
