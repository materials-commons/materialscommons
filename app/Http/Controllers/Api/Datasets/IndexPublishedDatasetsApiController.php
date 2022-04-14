<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;

class IndexPublishedDatasetsApiController extends Controller
{
    public function __invoke()
    {
        return DatasetResource::collection(Dataset::with(['rootDir', 'owner', 'tags'])
                                                  ->withCounts()
                                                  ->whereNotNull('published_at')
                                                  ->get());
    }
}
