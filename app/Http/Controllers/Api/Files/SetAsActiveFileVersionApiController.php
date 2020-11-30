<?php

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Project;

class SetAsActiveFileVersionApiController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        if ($file->current) {
            // Already active version nothing to do
            return new FileResource($file);
        }

        $file->setAsActiveFile();
        $file->fresh();

        return new FileResource($file);
    }
}
