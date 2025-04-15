<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\CreateDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\CreateDirectoryRequest;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;

class StoreFolderWebController extends Controller
{
    use DestinationProject;

    public function __invoke(CreateDirectoryRequest $request, CreateDirectoryAction $createDirectoryAction,
                             Project $project, File $folder)
    {
        $arg = $request->get('arg');
        $destProj = $this->getDestinationProjectId($project);
        $destDir = $this->getDestinationDirId();
        $validated = $request->validated();
        $createDirectoryAction->execute($validated, auth()->id());
        return redirect(route('projects.folders.show',
            [$project, $folder, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg]));
    }
}
