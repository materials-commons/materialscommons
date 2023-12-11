<?php

namespace App\Console\Commands\Disk;

use App\Models\File;
use Illuminate\Console\Command;
use function array_key_exists;

class CheckForMissingFilesDebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-disk:check-for-missing';

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
        foreach (File::with(['directory'])
                     ->where('mime_type', '<>', 'directory')
                     ->where('checksum', '0a388e241e36fcce9ae9adb81cf7c515')
                     ->cursor() as $file) {
            if (!$file->realFileExists()) {
                $missing++;
                if (!array_key_exists($file->project_id, $projectsWithMissingFiles)) {
                    $projectsWithMissingFiles[$file->project_id] = 1;
                } else {
                    $m = $projectsWithMissingFiles[$file->project_id];
                    $m++;
                    $projectsWithMissingFiles[$file->project_id] = $m;
                }
//                echo "{$file->directory->path}/{$file->name} in project {$file->project_id} does not exist\n";
            } else {
                echo "{$file->directory->path}/{$file->name} in {$file->project_id} exists\n";
            }
        }
        echo "Total missing files {$missing}\n";
        foreach ($projectsWithMissingFiles as $key => $value) {
            echo "Project {$key} missing {$value} files\n";
        }
        return Command::SUCCESS;
    }
}
