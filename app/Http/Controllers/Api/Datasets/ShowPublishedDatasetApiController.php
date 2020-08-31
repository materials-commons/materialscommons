<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Queries\Datasets\SingleDatasetQuery;
use App\Http\Resources\Datasets\DatasetResource;

class ShowPublishedDatasetApiController extends Controller
{
    public function __invoke(SingleDatasetQuery $query)
    {
        $dataset = $query->first();
        abort_if(is_null($dataset->published_at), 404);
        return new DatasetResource($dataset);
    }
}
