<?php

namespace App\Jobs\Folders;

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

    public $directoryIdsToMove;
    public File $fileIdsToMove;
    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($directoryIdsToMove, $fileIdsToMove, User $user)
    {
        $this->directoryIdsToMove = $directoryIdsToMove;
        $this->fileIdsToMove = $fileIdsToMove;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
