<?php

namespace App\Console\Commands\Disk;

use App\Models\File;
use Illuminate\Console\Command;

class CheckForMissingFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:check-for-missing-files';

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
        $missing = 0;
        foreach (File::with(['directory'])->cursor() as $file) {
            if (!$file->realFileExists()) {
                $missing++;
                echo "{$file->directory->path}/{$file->name} in project {$file->project_id} does not exist\n";
            }
        }
        echo "Total missing files {$missing}\n";
        
        return Command::SUCCESS;
    }
}
