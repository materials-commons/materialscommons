<?php

namespace App\Actions\Communities;

use App\Models\Community;
use App\Models\Dataset;

class UpdateCommunityDatasetSelectionAction
{
    public function execute(Community $community, $datasetId)
    {
        $dataset = Dataset::findOrFail($datasetId);
        abort_if(is_null($dataset->published_at), 404, "No such dataset");
        $community->datasets()->toggle($dataset->id);
        return $community->fresh();
    }
}