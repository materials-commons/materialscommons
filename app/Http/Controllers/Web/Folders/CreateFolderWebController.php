<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class CreateFolderWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, Project $project, File $folder)
    {
        $destProj = $this->getDestinationProjectId($project);
        $destDir = $this->getDestinationDirId();
        $arg = $request->get('arg');
        $directory = $folder; // View calls it a directory - fix this at some point
        return view('app.projects.folders.create', [
            'project'   => $project,
            'directory' => $directory,
            'arg'       => $arg,
            'destProj'  => $destProj,
            'destDir'   => $destDir,
        ]);
    }
}
