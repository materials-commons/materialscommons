<?php

namespace App\Http\Controllers\Api\Datasets\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Published\Datasets\IndexPublishedDatasetDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;
use App\Models\File;
use Illuminate\Http\Request;
use function abort_if;
use function is_null;

class IndexPublishedDatasetDirectoryByPathApiController extends Controller
{
    public function __invoke(Request $request, $datasetId)
    {
        $path = request()->input("path");
        $dir = File::where('dataset_id', $datasetId)
                   ->where('path', $path)
                   ->where('mime_type', 'directory')
                   ->first();
        abort_if(is_null($dir), 404, "No such directory path");
        $query = new IndexPublishedDatasetDirectoryQuery($request, $datasetId, $dir->id);
        return DirectoryResource::collection($query->get());
    }
}
