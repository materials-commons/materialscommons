<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use App\Traits\Projects\UserProjects;

class MoveFilesWebController extends Controller
{
    use GetProjectFolderFiles;
    use UserProjects;

    public function __invoke(Project $project, $folderId)
    {
        $directory = File::where('project_id', $project->id)->where('id', $folderId)->first();
        $files = $this->getProjectFolderFiles($project->id, $folderId);
        $projects = $this->getUserProjects(auth()->id());
        $dirsInProject = File::where('project_id', $project->id)
                             ->where('mime_type', 'directory')
                             ->whereNull('dataset_id')
                             ->whereNull('deleted_at')
                             ->where('current', true)
                             ->where('id', '<>', $folderId)
                             ->get();
        return view('app.projects.folders.move', compact('project', 'directory', 'files', 'dirsInProject', 'projects'));
    }
}
