<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\DeleteDirectoryAction;
use App\Http\Controllers\Controller;
use App\Models\File;

class DeleteDirectoryApiController extends Controller
{
    public function __invoke(DeleteDirectoryAction $deleteDirectoryAction, $projectId, $directoryId)
    {
        $directory = File::findOrFail($directoryId);
        $deleteDirectoryAction($directory);
    }
}
