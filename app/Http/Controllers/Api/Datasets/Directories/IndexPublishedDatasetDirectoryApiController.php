<?php

namespace App\Http\Controllers\Api\Datasets\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Published\Datasets\IndexPublishedDatasetDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;
use Illuminate\Http\Request;

class IndexPublishedDatasetDirectoryApiController extends Controller
{
    public function __invoke(Request $request, $datasetId, $directoryId)
    {
        $query = new IndexPublishedDatasetDirectoryQuery($request, $datasetId, $directoryId);
        return DirectoryResource::collection($query->get());
    }
}
