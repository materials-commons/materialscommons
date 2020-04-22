<?php

namespace App\Http\Controllers\Api\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Directories\SingleDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;

class ShowDirectoryApiController extends Controller
{
    public function __invoke(SingleDirectoryQuery $query, $projectId)
    {
        $data = $query->first();
        return new DirectoryResource($data);
    }
}
