<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\RenameFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class UpdateRenameFileWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, RenameFileAction $renameFileAction, Project $project, File $file)
    {
        $arg = $request->get('arg');
        $destProj = $this->getDestinationProjectId($project);
        $destDir = $this->getDestinationDirId();

        $file->load('directory');

        $name = $request->input('name');
        $file = $renameFileAction($file, $name);
        return redirect(route('projects.folders.show',
            [$project, $file->directory, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => $arg]));
    }
}
