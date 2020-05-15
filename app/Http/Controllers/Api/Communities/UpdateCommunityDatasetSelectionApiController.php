<?php

namespace App\Http\Controllers\Api\Communities;

use App\Actions\Communities\UpdateCommunityDatasetSelectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communities\UpdateCommunityDatasetRequest;
use App\Models\Community;

class UpdateCommunityDatasetSelectionApiController extends Controller
{
    public function __invoke(UpdateCommunityDatasetRequest $request,
        UpdateCommunityDatasetSelectionAction $updateCommunityDatasetSelectionAction, Community $community)
    {
        $validated = $request->validated();
        abort_unless($community->owner_id == auth()->id(), 404, "No such dataset");
        return $updateCommunityDatasetSelectionAction->execute($community, $validated['dataset_id']);
    }
}
