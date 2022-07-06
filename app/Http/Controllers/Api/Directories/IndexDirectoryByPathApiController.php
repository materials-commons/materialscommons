<?php

namespace App\Http\Controllers\Api\Directories;

use App\Http\Controllers\Controller;
use App\Http\Queries\Directories\IndexDirectoryQuery;
use App\Http\Resources\Directories\DirectoryResource;
use App\Models\File;
use Illuminate\Http\Request;

class IndexDirectoryByPathApiController extends Controller
{
    public function __invoke(Request $request, $projectId)
    {
        $path = request()->input("path");
        $dir = File::where('project_id', $projectId)
                   ->where('path', $path)
                   ->where('mime_type', 'directory')
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->where('current', true)
                   ->first();
        abort_if(is_null($dir), 404, "No such directory path");
        $query = new IndexDirectoryQuery($request, $projectId, $dir->id);
        return DirectoryResource::collection($query->get());
    }
}
