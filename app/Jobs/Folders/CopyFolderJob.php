<?php

namespace App\Jobs\Folders;

use App\Actions\Directories\CopyDirectoryAction;
use App\Models\File;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CopyFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public File $folderToCopy;
    public File $destinationFolder;
    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(File $folderToCopy, File $destinationFolder, User $user)
    {
        $this->folderToCopy = $folderToCopy;
        $this->destinationFolder = $destinationFolder;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $copyDirAction = new CopyDirectoryAction();
        $copyDirAction->execute($this->folderToCopy, $this->destinationFolder, $this->user);
    }
}
