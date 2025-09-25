<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class GotoFolderByPathInParam extends Controller
{
    public function __invoke(Project $project)
    {
        $path = request()->input("path");
        $dir = File::where('project_id', $project->id)
                   ->where('path', $path)
                   ->directories()
                   ->active()
                   ->first();
        return redirect(route('projects.folders.show', [$project, $dir]));
    }
}
