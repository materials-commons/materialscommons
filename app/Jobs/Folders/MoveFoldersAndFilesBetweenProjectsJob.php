<?php

namespace App\Jobs\Folders;

use App\Actions\Directories\MoveDirectoryAction;
use App\Actions\Files\MoveFileAction;
use App\Models\File;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoveFoldersAndFilesBetweenProjectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $idsToMove;
    public $destinationDirId;
    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($idsToMove, $destinationDirId, User $user)
    {
        $this->idsToMove = $idsToMove;
        $this->destinationDirId = $destinationDirId;
        $this->user = $user;
    }

    /**
     * Moves files and directories to the specified destination directory. Access checks
     * for the project should have been performed before this job is called.
     */
    public function handle(): void
    {
        $dirsToMove = File::whereIn('id', $this->idsToMove)
                          ->whereNotNull('path')
                          ->where('mime_type', 'directory')
                          ->whereNull('dataset_id')
                          ->whereNull('deleted_at')
                          ->where('current', true)
                          ->get();

        $filesToMove = File::whereIn('id', $this->idsToMove)
                           ->where('mime_type', '<>', 'directory')
                           ->get();

        $moveToDirectory = File::find($this->destinationDirId);
        if (is_null($moveToDirectory)) {
            return;
        }

        $moveFileAction = new MoveFileAction();
        $filesToMove->each(function ($file) use ($moveToDirectory, $moveFileAction) {
            $moveFileAction($file, $moveToDirectory, $this->user);
        });

        $moveDirectoryAction = new MoveDirectoryAction();
        $dirsToMove->each(function ($dir) use ($moveToDirectory, $moveDirectoryAction) {
            $moveDirectoryAction($dir->id, $moveToDirectory->id, $this->user);
        });
    }
}
