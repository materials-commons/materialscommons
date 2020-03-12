<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use Illuminate\Http\Request;

class ShowUploadFilesWebController extends Controller
{
    use GetProjectFolderFiles;

    public function __invoke(Request $request, Project $project)
    {
        $folders = $this->getProjectFolders($project->id);
        return view('app.projects.upload-files', compact('project', 'folders'));
    }
}
