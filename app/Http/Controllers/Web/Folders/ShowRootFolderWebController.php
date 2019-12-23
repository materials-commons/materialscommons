<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;

class ShowRootFolderWebController extends Controller
{
    use GetProjectFolderFiles;

    public function __invoke(Project $project)
    {
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        $files = $this->getProjectFolderFiles($project->id, '/');
        return view('app.projects.folders.show', compact('project', 'directory', 'files'));
    }
}
