<?php

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Project;

class ShowFileVersionsApiController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        return FileResource::collection($file->previousVersions()->get());
    }
}
