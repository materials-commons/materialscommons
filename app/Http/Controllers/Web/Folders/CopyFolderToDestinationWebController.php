<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\CopyDirectoryAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class CopyFolderToDestinationWebController extends Controller
{
    public function __invoke(CopyDirectoryAction $copyDirectoryAction, Project $project, File $fromFolder,
                             File                $toFolder)
    {
        // run in the background
        $copyDirectoryAction->execute($fromFolder, $toFolder, auth()->user());

        flash("Copying directory will take place in the background")->info();
        // Start move in the background and then return to the folder that contains the folder we are copying.
        return redirect(route('projects.folders.show', [$fromFolder->project_id, $fromFolder->directory_id]));
    }
}
