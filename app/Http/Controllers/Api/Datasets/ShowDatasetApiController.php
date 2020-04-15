<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Queries\Datasets\SingleDatasetQuery;
use App\Http\Resources\Datasets\DatasetResource;

class ShowDatasetApiController extends Controller
{
    public function __invoke(SingleDatasetQuery $query)
    {
        $data = $query->first();
        return new DatasetResource($data);
    }
}
