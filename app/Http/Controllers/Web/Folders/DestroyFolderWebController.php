<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\DeleteDirectoryAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class DestroyFolderWebController extends Controller
{
    public function __invoke(DeleteDirectoryAction $deleteDirectoryAction, Project $project, $dirId)
    {
        $dir = File::with('directory')->findOrFail($dirId);
        $parent = $dir->directory;
        $deleteDirectoryAction($dir);
        return redirect(route('projects.folders.show', [$project, $parent]));
    }
}
