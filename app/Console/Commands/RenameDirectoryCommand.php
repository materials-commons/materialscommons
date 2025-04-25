<?php

namespace App\Console\Commands;

use App\Actions\Directories\RenameDirectoryAction;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RenameDirectoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:rename-directory {directoryId} {newName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename a directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directoryId = $this->argument('directoryId');
        $newName = $this->argument('newName');
        if (Str::contains($newName, '/')) {
            $this->error('New name cannot contain a /');
            return 0;
        }
        $renameDirectoryAction = new RenameDirectoryAction();
        $renameDirectoryAction($directoryId, $newName);
    }
}
