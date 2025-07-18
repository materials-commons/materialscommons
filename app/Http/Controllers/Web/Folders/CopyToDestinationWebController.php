<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Files\CopyFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CopyMoveFilesRequest;
use App\Jobs\Folders\CopyFolderJob;
use App\Models\File;
use App\Models\Project;
use App\Services\AuthService;
use App\Traits\Folders\DestinationProject;
use function auth;
use function flash;
use function route;

class CopyToDestinationWebController extends Controller
{
    use DestinationProject;

    public function __invoke(CopyMoveFilesRequest $request, Project $project, $folderId)
    {
        $destProj = $this->getDestinationProject($project);
        $destDir = $this->getDestinationDirId();
        $user = auth()->user();
        $validated = $request->validated();
        $ids = $validated['ids'];
        $copyToDirectoryId = $validated['directory'];
        $copyToDirectory = File::find($copyToDirectoryId);
        $redirectRoute = route('projects.folders.show',
            [$project, $folderId, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => 'move-copy']);

        if ($copyToDirectory->project_id !== $project->id) {
            if (!AuthService::userCanAccessProjectId($user, $copyToDirectory->project_id)) {
                flash()->error('You do not have access to that project.');
                return redirect($redirectRoute);
            }
        }

        $this->copyFilesAndDirectories($ids, $copyToDirectory, $user);

        $toProj = $destProj->id == $project->id ? "this project" : "project {$destProj->name}";
        flash()->success("Files and directories are being copied in the background to {$copyToDirectory->path} in {$toProj}.");

        return redirect($redirectRoute);
    }

    private function copyFilesAndDirectories($ids, $copyToDirectory, $user)
    {

        // We assume that file copies are cheap and just copy in place rather than
        // running them as background jobs.
        $filesToCopy = File::whereIn('id', $ids)
                           ->where('mime_type', '<>', 'directory')
                           ->get();
        $copyFileAction = new CopyFileAction();
        $filesToCopy->each(function ($file) use ($copyToDirectory, $user, $copyFileAction) {
            if ($file->directory_id == $copyToDirectory->id) {
                // Don't let a file be copied to the same directory it's already in
                return;
            }
            $copyFileAction->execute($file, $copyToDirectory, $user);
        });

        // Copying folders can take a while as we have to recursively descend through them,
        // so these run as background jobs.
        $dirsToCopy = File::whereIn('id', $ids)
                          ->whereNotNull('path')
                          ->where('mime_type', 'directory')
                          ->whereNull('dataset_id')
                          ->whereNull('deleted_at')
                          ->where('current', true)
                          ->get();
        $dirsToCopy->each(function ($dir) use ($copyToDirectory, $user) {
            if ($dir->id == $copyToDirectory->id) {
                // Don't allow a directory to be copied to itself
                return;
            }
            CopyFolderJob::dispatch($dir, $copyToDirectory, $user)->onQueue('globus');
        });
    }
}
