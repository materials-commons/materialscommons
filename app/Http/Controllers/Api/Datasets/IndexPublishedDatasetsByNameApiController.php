<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use Illuminate\Http\Request;

class IndexPublishedDatasetsByNameApiController extends Controller
{
    public function __invoke()
    {
        $publishedAtName = "published_at";
        if (request()->has('test')) {
            $publishedAtName = "test_published_at";
        }

        return DatasetResource::collection(Dataset::with(['rootDir', 'owner', 'tags'])
                                                  ->withCounts()
                                                  ->whereNotNull($publishedAtName)
                                                  ->where('name', request()->input('name'))
                                                  ->get());
    }
}
