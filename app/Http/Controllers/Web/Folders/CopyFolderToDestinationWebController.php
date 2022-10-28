<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\CopyDirectoryAction;
use App\Http\Controllers\Controller;
use App\Jobs\Folders\CopyFolderJob;
use App\Models\File;
use App\Models\Project;

class CopyFolderToDestinationWebController extends Controller
{
    public function __invoke(CopyDirectoryAction $copyDirectoryAction, Project $project, File $fromFolder,
                             File                $toFolder)
    {
        // Start copy in the background and then return to the folder that contains the folder we are copying. Let
        // the user know the copy will be done in the background.
        CopyFolderJob::dispatch($fromFolder, $toFolder, auth()->user())->onQueue('globus');
        flash("Copying directory will take place in the background")->info();
        return redirect(route('projects.folders.show', [$fromFolder->project_id, $fromFolder->directory_id]));
    }
}
