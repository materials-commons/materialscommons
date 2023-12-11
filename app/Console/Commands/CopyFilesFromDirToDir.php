<?php

namespace App\Console\Commands;

use App\Actions\Directories\CopyDirectoryAction;
use App\Models\File;
use App\Models\User;
use Illuminate\Console\Command;

class CopyFilesFromDirToDir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:copy-files-from-dir-to-dir {fromDirId} {toDirId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fromDir = File::findOrFail($this->argument('fromDirId'));
        $toDir = File::findOrFail($this->argument('toDirId'));
        $user = User::findOrFail($toDir->owner_id);
        $copyDirAction = new CopyDirectoryAction();
        $copyDirAction->execute($fromDir, $toDir, $user);
        return Command::SUCCESS;
    }
}
