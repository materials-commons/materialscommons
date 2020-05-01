<?php

namespace App\Actions\Communities;

use App\Models\Community;
use App\Models\Dataset;

class UpdateCommunityDatasetSelectionAction
{
    public function execute($communityId, $datasetId, $userId)
    {
        $community = Community::findOrFail($communityId);
        $dataset = Dataset::findOrFail($datasetId);
        abort_if(is_null($dataset->published_at), 404, "No such dataset");
        abort_unless($community->owner_id == $userId, 401, "Not authorized");
        $community->datasets()->toggle($dataset->id);
        return $community;
    }
}