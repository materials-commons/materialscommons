<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;

class ShowPublishedDatasetApiController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with(['owner', 'tags', 'rootDir'])
                          ->withCounts()
                          ->where('id', $datasetId);
        // if published_at is null then this dataset is not in a published state.
        abort_if(is_null($dataset->published_at), 404);
        return new DatasetResource($dataset);
    }
}
