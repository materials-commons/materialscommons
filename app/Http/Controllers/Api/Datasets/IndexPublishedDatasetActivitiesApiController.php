<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activities\ActivityResource;
use App\Models\Dataset;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPublishedDatasetActivitiesApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        abort_if(is_null($dataset->published_at), 404);
        $query = $dataset->activities()->with('attributes.values')->getQuery();
        return ActivityResource::collection(QueryBuilder::for($query)->get());
    }
}
