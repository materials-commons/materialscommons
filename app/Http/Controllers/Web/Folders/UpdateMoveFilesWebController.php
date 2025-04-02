<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\MoveDirectoryAction;
use App\Actions\Files\MoveFileAction;
use App\Helpers\PathHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\MoveFilesRequest;
use App\Jobs\Folders\MoveFoldersAndFilesBetweenProjectsJob;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Services\AuthService;
use function redirect;
use function route;

class UpdateMoveFilesWebController extends Controller
{
    public function __invoke(MoveFilesRequest $request, MoveDirectoryAction $moveDirectoryAction,
                             MoveFileAction $moveFileAction, Project $project, $folderId,
                             ?Project       $destinationProject = null)
    {
        if (is_null($destinationProject)) {
            $destinationProject = $project;
        }

        $user = auth()->user();

        $validated = $request->validated();
        $ids = $validated['ids'];
        $moveToDirectoryId = $validated['directory'];

        $dirsToMove = File::whereIn('id', $ids)
                          ->whereNotNull('path')
                          ->whereNull('dataset_id')
                          ->whereNull('deleted_at')
                          ->where('current', true)
                          ->get();

        $moveToDirectory = File::find($moveToDirectoryId);

        if ($destinationProject->id !== $project->id) {
            if (!AuthService::userCanAccessProjectId($user, $destinationProject->id)) {
                // TODO: Add Flash on no such project
                redirect(route('projects.folders.show',
                    [$project, $folderId, $destinationProject, 'arg' => 'move-copy']));
            }
            // Move the top level folders and then submit job to complete the moves
            $this->moveToplevelFolders($dirsToMove, $moveToDirectory, $user);
            MoveFoldersAndFilesBetweenProjectsJob::dispatch($ids, $moveToDirectoryId, $user);

            return redirect(route('projects.folders.show',
                [$project, $folderId, $destinationProject, 'arg' => 'move-copy']));
        }

        $filesToMove = File::whereIn('id', $ids)
                           ->whereNull('path')
                           ->get();
        $filesToMove->each(function ($file) use ($moveToDirectory, $moveFileAction) {
            $moveFileAction($file, $moveToDirectory);
        });

        $dirsToMove->each(function ($dir) use ($moveToDirectory, $moveDirectoryAction, $user) {
            $moveDirectoryAction($dir->id, $moveToDirectory, $user);
        });

        return redirect(route('projects.folders.show',
            [$project, $folderId, $destinationProject, 'arg' => 'move-copy']));
    }

    private function moveToplevelFolders($dirIdsToMove, $moveToDirectory, User $user)
    {
        if (!AuthService::userCanAccessProjectId($user, $moveToDirectory->project_id)) {
            return;
        }

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
}
