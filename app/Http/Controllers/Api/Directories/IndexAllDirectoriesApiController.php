<?php

namespace App\Http\Controllers\Api\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Directories\IndexAllDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;
use Illuminate\Http\Request;

class IndexAllDirectoriesApiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $projectId)
    {
        $query = new IndexAllDirectoryQuery($request, $projectId);
        return DirectoryResource::collection($query->get());
    }
}
