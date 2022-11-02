<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Files\CopyFileAction;
use App\Http\Controllers\Controller;
use App\Jobs\Folders\CopyFolderJob;
use App\Models\File;
use App\Models\Project;
use function auth;
use function flash;

class CopyToDestinationWebController extends Controller
{
    public function __invoke(Project $project, File $from, File $toFolder, $copyType)
    {
        switch ($copyType) {
            case "copy-dir":
                // Start copy in the background and then return to the folder that contains the folder we are copying. Let
                // the user know the copy will be done in the background.
                CopyFolderJob::dispatch($from, $toFolder, auth()->user())->onQueue('globus');
                flash("Copying directory will take place in the background")->info();
                break;

            case "copy-file":
                flash("Copied file {$from->name} to '{$toFolder->path}' in project '{$project->name}'.")->info();
                $copyFileAction = new CopyFileAction();
                $copyFileAction->execute($from, $toFolder, auth()->user());
                break;
        }

        return redirect(route('projects.folders.show', [$from->project_id, $from->directory_id]));
    }
}
