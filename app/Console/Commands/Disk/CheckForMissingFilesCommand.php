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
    protected $signature = 'mc-disk:check-for-missing-files {project_id? : Optional project id to check}';

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
        ini_set("memory_limit", "4096M");
        $missing = 0;
        $projectsWithMissingFiles = [];
        $query = File::with(['directory'])->where('mime_type', '<>', 'directory');
        $projectId = $this->argument('project_id');
        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $outPath = "/tmp/missing_files.txt";
        $handle = fopen($outPath, "w");

        foreach ($query->cursor() as $file) {
            if (!$file->realFileExists()) {
                $missing++;
                if (!array_key_exists($file->project_id, $projectsWithMissingFiles)) {
                    $projectsWithMissingFiles[$file->project_id] = 1;
                } else {
                    $m = $projectsWithMissingFiles[$file->project_id];
                    $m++;
                    $projectsWithMissingFiles[$file->project_id] = $m;
                }
                echo "{$file->directory->path}/{$file->name} ({$file->id}) in project {$file->project_id} does not exist\n";
                if ($handle) {
                    $content = "{$file->directory->path}/{$file->name}:{$file->id}:{$file->uuid}:{$file->uses_uuid}:{$file->created_at->toDateString()}:{$file->project_id}\n";
                    fwrite($handle, $content);
                }
            }
        }

        fclose($handle);
        echo "Total missing files {$missing}\n";
        foreach ($projectsWithMissingFiles as $key => $value) {
            echo "Project {$key} missing {$value} files\n";
        }

        return Command::SUCCESS;
    }
}
