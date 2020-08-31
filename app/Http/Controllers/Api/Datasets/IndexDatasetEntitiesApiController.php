<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\EntityResource;
use App\Models\Dataset;
use Spatie\QueryBuilder\QueryBuilder;

class IndexDatasetEntitiesApiController extends Controller
{
    public function __invoke($projectId, Dataset $dataset)
    {
        $query = $dataset->entities()->with('entityStates.attributes.values')->getQuery();
        return EntityResource::collection(QueryBuilder::for($query)->get());
    }
}
