<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\Dataset;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPublishedDatasetFilesApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        abort_if(is_null($dataset->published_at), 404);
        return FileResource::collection(QueryBuilder::for($dataset->files()->getQuery())->get());
    }
}
