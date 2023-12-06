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
        $projectsWithMissingFiles = [];
        foreach (File::with(['directory'])->where('mime_type', '<>', 'directory')->cursor() as $file) {
            if (!$file->realFileExists()) {
                $missing++;
                if (!array_key_exists($file->project_id, $projectsWithMissingFiles)) {
                    $projectsWithMissingFiles[$file->project_id] = 1;
                } else {
                    $m = $projectsWithMissingFiles[$file->project_id];
                    $m++;
                    $projectsWithMissingFiles[$file->project_id] = $m;
                }
                echo "{$file->directory->path}/{$file->name} in project {$file->project_id} does not exist\n";
            }
        }
        echo "Total missing files {$missing}\n";
        foreach ($projectsWithMissingFiles as $key => $value) {
            echo "Project {$key} missing {$value} files\n";
        }

        return Command::SUCCESS;
    }
}
