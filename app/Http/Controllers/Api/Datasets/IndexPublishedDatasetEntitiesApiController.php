<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\EntityResource;
use App\Models\Dataset;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPublishedDatasetEntitiesApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        abort_if(is_null($dataset->published_at), 404);
        $query = $dataset->entities()->with('entityStates.attributes.values')->getQuery();
        return EntityResource::collection(QueryBuilder::for($query)->get());
    }
}
