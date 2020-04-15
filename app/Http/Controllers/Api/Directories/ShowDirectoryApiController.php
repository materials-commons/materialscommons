<?php

namespace App\Http\Controllers\Api\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Directories\SingleDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;

class ShowDirectoryApiController extends Controller
{
    /**
     * Show the directory
     *
     * @param  SingleDirectoryQuery  $query
     * @return DirectoryResource
     */
    public function __invoke(SingleDirectoryQuery $query)
    {
        $data = $query->first();
        return new DirectoryResource($data);
    }
}
