<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\Dataset;
use Spatie\QueryBuilder\QueryBuilder;

class IndexDatasetFilesApiController extends Controller
{
    public function __invoke($projectId, Dataset $dataset)
    {
        return FileResource::collection(QueryBuilder::for($dataset->files()->getQuery())->get());
    }
}
