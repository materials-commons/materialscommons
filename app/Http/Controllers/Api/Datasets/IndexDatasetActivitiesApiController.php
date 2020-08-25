<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Dataset;
use Spatie\QueryBuilder\QueryBuilder;

class IndexDatasetActivitiesApiController extends Controller
{
    public function __invoke($projectId, Dataset $dataset)
    {
        $query = $dataset->activities()->with('attributes.values')->getQuery();
        return ActivityResource::collection(QueryBuilder::for($query)->get());
    }
}
