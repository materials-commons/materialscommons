<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\MoveDirectoryAction;
use App\Actions\Files\MoveFileAction;
use App\Helpers\PathHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CopyMoveFilesRequest;
use App\Jobs\Folders\MoveFoldersAndFilesBetweenProjectsJob;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\Folders\DestinationProject;
use function flash;
use function redirect;
use function route;

class UpdateMoveFilesWebController extends Controller
{
    use DestinationProject;

    public function __invoke(CopyMoveFilesRequest $request, Project $project, $folderId)
    {
        $destProj = $this->getDestinationProject($project);
        $destDir = $this->getDestinationDirId();

        $user = auth()->user();

        $validated = $request->validated();
        $ids = $validated['ids'];
        $moveToDirectoryId = $validated['directory'];
        $moveToDirectory = File::find($moveToDirectoryId);

        $redirectRoute = route('projects.folders.show',
            [$project, $folderId, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => 'move-copy']);

        if ($moveToDirectory->project_id !== $project->id) {
            // Make sure the user has access to the project.
            if (!AuthService::userCanAccessProjectId($user, $moveToDirectory->project_id)) {
                flash()->error('You do not have access to that project.');
                return redirect($redirectRoute);
            }
            $this->moveBetweenProjects($ids, $moveToDirectory, $user);
        } else {
            $this->moveInProject($ids, $moveToDirectory, $user);
        }

        $toProj = $moveToDirectory->project_id == $project->id ? "this project" : "project {$destProj->name}";
        flash()->success("Files and directories are being moved in the background to {$moveToDirectory->path} in {$toProj}.");

        return redirect($redirectRoute);
    }

    // moveBetweenProjects doesn't need to check if a directory is being moved into itself, or a file
    // is being copied into the same directory because by definition this is a move between projects.
    // That means the ids will always be different.
    private function moveBetweenProjects($ids, $moveToDirectory, User $user)
    {
        $dirsToMove = File::whereIn('id', $ids)
                          ->whereNotNull('path')
                          ->where('mime_type', 'directory')
                          ->whereNull('dataset_id')
                          ->whereNull('deleted_at')
                          ->where('current', true)
                          ->get();
        // Move the top level folders and then submit a job to complete the moves. We move
        // the top level folders before running the background job so that the UI will
        // immediately show the move taking place. That prevents the user from getting
        // confused whether the move actually worked.

        $this->moveToplevelFolders($dirsToMove, $moveToDirectory, $user);
        MoveFoldersAndFilesBetweenProjectsJob::dispatch($ids, $moveToDirectory->id, $user);
    }

    private function moveToplevelFolders($dirIdsToMove, $moveToDirectory, User $user)
    {
        foreach ($dirIdsToMove as $dirIdToMove) {
            $dir = File::find($dirIdToMove);
            $pathToUse = PathHelpers::normalizePath("{$moveToDirectory->path}/{$dir->name}");
            File::find($dirIdToMove)->update([
                'directory_id' => $moveToDirectory->id,
                'path'         => $pathToUse,
                'project_id'   => $moveToDirectory->project_id,
            ]);
        }
    }

    private function moveInProject($ids, $moveToDirectory, User $user)
    {
        $moveFileAction = new MoveFileAction();
        $filesToMove = File::whereIn('id', $ids)
                           ->where('mime_type', '<>', 'directory')
                           ->get();

        $filesToMove->each(function ($file) use ($moveToDirectory, $moveFileAction, $user) {
            if ($file->directory_id == $moveToDirectory->id) {
                // Don't let a file be copied into the same directory it's already in
                return;
            }
            $moveFileAction($file, $moveToDirectory, $user);
        });

        $dirsToMove = File::whereIn('id', $ids)
                          ->whereNotNull('path')
                          ->where('mime_type', 'directory')
                          ->whereNull('dataset_id')
                          ->whereNull('deleted_at')
                          ->where('current', true)
                          ->get();
        $moveDirectoryAction = new MoveDirectoryAction();
        $dirsToMove->each(function ($dir) use ($moveToDirectory, $moveDirectoryAction, $user) {
            if ($dir->id == $moveToDirectory->id) {
                // Don't copy a directory into itself
                return;
            }
            $moveDirectoryAction($dir->id, $moveToDirectory->id, $user);
        });
    }
}
