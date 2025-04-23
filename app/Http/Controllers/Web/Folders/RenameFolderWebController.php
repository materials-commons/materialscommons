<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class RenameFolderWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, Project $project, File $dir)
    {
        $arg = $request->get('arg');
        $destProj = $this->getDestinationProjectId($project);
        $destDir = $this->getDestinationDirId();
        return view('app.projects.folders.rename', [
            'project'  => $project,
            'dir'      => $dir,
            'arg'      => $arg,
            'destProj' => $destProj,
            'destDir'  => $destDir,
        ]);
    }
}
