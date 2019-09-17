<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class ShowFolderWebController extends Controller
{
    public function __invoke(Project $project, $folderId)
    {
        $dir = File::where('project_id', $project->id)->where('id', $folderId)->first();
        return view('app.projects.folders.show', ['project' => $project, 'directory' => $dir]);
    }

    private function showRootFolder(Project $project)
    {
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        return view('app.projects.folders.show', compact('project', 'directory'));
    }
}
