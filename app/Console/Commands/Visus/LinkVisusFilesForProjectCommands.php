<?php

namespace App\Console\Commands\Visus;

use App\Models\File;
use App\Models\Project;
use App\Traits\PathForFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LinkVisusFilesForProjectCommands extends Command
{
    use PathForFile;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-visus:link-visus-files-for-project {projectId: project to setup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private array $knownDirectories = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $project = Project::findOrFail($this->argument('projectId'));
        $allDirsCursor = File::where('project_id', $project->id)
                             ->where('mime_type', 'directory')
                             ->where('current', true)
                             ->orderBy('path')
                             ->cursor();
        $baseDir = Storage::disk('mcfs')->path("__visus/{$project->uuid}");

        foreach ($allDirsCursor as $dir) {
            $this->createDirIfNotExists("__visus/{$project->uuid}{$dir->path}");
            $filesCursor = File::where('directory_id', $dir->id)
                               ->where('current', true)
                               ->whereNull('path')
                               ->cursor();
            foreach ($filesCursor as $file) {
                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                $filePath = "{$baseDir}{$dir->path}/{$file->name}";
                try {
                    $dirPathForFilePartial = "__visus/{$project->uuid}{$dir->path}";
                    $this->createDirIfNotExists($dirPathForFilePartial);
                    if (!link($uuidPath, $filePath)) {
                        echo "Unable to link {$uuidPath} to {$filePath}\n";
                        Log::error("Unable to link {$uuidPath} to {$filePath}");
                    }
                } catch (\Exception $e) {
                    echo "Unable to link {$uuidPath} to {$filePath}\n";
                    Log::error("Unable to link {$uuidPath} to {$filePath}");
                }
            }
        }
        return 0;
    }

    private function createDirIfNotExists($dirPath)
    {
        if (array_key_exists($dirPath, $this->knownDirectories)) {
            return;
        }

        $fullPath = Storage::disk('mcfs')->path($dirPath);
        if (Storage::disk('mcfs')->exists($dirPath)) {
            $this->knownDirectories[$dirPath] = true;
            return;
        }

        Storage::disk('mcfs')->makeDirectory($dirPath);
        $p = Storage::disk('mcfs')->path($dirPath);
        chmod($p, 0777);
        $this->knownDirectories[$dirPath] = true;
    }
}
