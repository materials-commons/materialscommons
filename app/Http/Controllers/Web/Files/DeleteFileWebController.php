<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class DeleteFileWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, Project $project, File $file)
    {
        $file->load('directory');
        $destProj = $this->getDestinationProject($project);
        $destDir = $this->getDestinationDirId();
        $arg = $request->input('arg');
        return view('app.projects.files.delete', [
            'project'  => $project,
            'file'     => $file,
            'dir'      => $file->directory,
            'destProj' => $destProj,
            'destDir'  => $destDir,
            'arg'      => $arg,
        ]);
    }
}
