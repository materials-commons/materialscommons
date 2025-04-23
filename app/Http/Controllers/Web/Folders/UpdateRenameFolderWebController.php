<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\RenameDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\RenameDirectoryRequest;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;

class UpdateRenameFolderWebController extends Controller
{
    use DestinationProject;

    public function __invoke(RenameDirectoryRequest $request, RenameDirectoryAction $renameDirectoryAction,
                             Project $project, File $dir)
    {
        $arg = $request->get('arg');
        $destProj = $this->getDestinationProjectId($project);
        $destDir = $this->getDestinationDirId();
        $renameDirectoryAction($dir->id, $request->input('name'));
        return redirect(route('projects.folders.show',
            [$project, $dir, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => $arg]));
    }
}
