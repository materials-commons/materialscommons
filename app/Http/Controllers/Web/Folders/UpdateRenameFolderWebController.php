<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\RenameDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\RenameDirectoryRequest;
use App\Models\File;
use App\Models\Project;

class UpdateRenameFolderWebController extends Controller
{
    public function __invoke(RenameDirectoryRequest $request, RenameDirectoryAction $renameDirectoryAction,
        Project $project, File $dir)
    {
        $renameDirectoryAction($dir->id, $request->input('name'));
        return redirect(route('projects.folders.show', [$project, $dir]));
    }
}
