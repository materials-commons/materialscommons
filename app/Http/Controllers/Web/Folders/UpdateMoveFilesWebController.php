<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\MoveDirectoryAction;
use App\Actions\Files\MoveFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\MoveFilesRequest;
use App\Models\File;
use App\Models\Project;

class UpdateMoveFilesWebController extends Controller
{
    public function __invoke(MoveFilesRequest $request, MoveDirectoryAction $moveDirectoryAction,
                             MoveFileAction $moveFileAction, Project $project, $folderId,
                             ?Project       $destinationProject = null)
    {
        if (is_null($destinationProject)) {
            $destinationProject = $project;
        }

        $validated = $request->validated();
        $ids = $validated['ids'];
        $moveToDirectory = $validated['directory'];

        $filesToMove = File::whereIn('id', $ids)
                           ->whereNull('path')
                           ->get();
        $filesToMove->each(function ($file) use ($moveToDirectory, $moveFileAction) {
            $moveFileAction($file, $moveToDirectory);
        });

        $dirsToMove = File::whereIn('id', $ids)
                          ->whereNotNull('path')
                          ->whereNull('dataset_id')
                          ->whereNull('deleted_at')
                          ->where('current', true)
                          ->get();
        $dirsToMove->each(function ($dir) use ($moveToDirectory, $moveDirectoryAction) {
            $moveDirectoryAction($dir->id, $moveToDirectory);
        });

        return redirect(route('projects.folders.show',
            [$project, $folderId, $destinationProject, 'arg' => 'move-copy']));
    }
}
