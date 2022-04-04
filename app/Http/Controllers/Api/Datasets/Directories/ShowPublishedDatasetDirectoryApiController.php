<?php

namespace App\Http\Controllers\Api\Datasets\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Published\Datasets\SinglePublishedDatasetDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;
use Illuminate\Http\Request;

class ShowPublishedDatasetDirectoryApiController extends Controller
{
    public function __invoke(Request $request, $datasetId, $directoryId)
    {
        $query = new SinglePublishedDatasetDirectoryQuery($datasetId, $directoryId, $request);
        return new DirectoryResource($query->first());
    }
}
