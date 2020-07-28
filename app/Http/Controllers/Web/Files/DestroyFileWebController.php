<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\DeleteFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class DestroyFileWebController extends Controller
{
    public function __invoke(DeleteFileAction $deleteFileAction, Project $project, $fileId)
    {
        $file = File::with('directory')->findOrFail($fileId);
        $dir = $file->directory;
        $deleteFileAction($file);
        return redirect(route('projects.folders.show', [$project, $dir]));
    }
}
