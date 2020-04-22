<?php

namespace App\Http\Controllers\Api\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Directories\IndexDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;
use Illuminate\Http\Request;

class IndexDirectoryApiController extends Controller
{
    public function __invoke(Request $request, $projectId, $directoryId)
    {
        $query = new IndexDirectoryQuery($request, $projectId, $directoryId);
        return DirectoryResource::collection($query->get());
    }
}
