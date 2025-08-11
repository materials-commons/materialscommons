<?php

namespace App\Actions\Projects;

use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\Project;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function array_key_exists;
use function chmod;
use function link;

class CreateProjectFilesAtLocationAction
{
    use PathForFile;

    private $knownDirectories;
    private $disk;

    public function execute(Project $project, $disk, $location)
    {
        $this->knownDirectories = [];
        $this->disk = $disk;

        if (!Str::endsWith($location, "/")) {
            $location = "{$location}/";
        }

        $allDirs = File::where('project_id', $project->id)
                       ->where('mime_type', 'directory')
                       ->where('current', true)
                       ->whereNull('deleted_at')
                       ->whereNull('dataset_id')
                       ->orderBy('path')
                       ->cursor();
        $baseLocationDir = PathHelpers::normalizePath(Storage::disk($disk)->path($location));

        foreach ($allDirs as $dir) {
            $this->createDirIfNotExists("{$location}{$dir->path}");
            $files = File::where('directory_id', $dir->id)
                         ->whereNull('deleted_at')
                         ->whereNull('dataset_id')
                         ->where('current', true)
                         ->where('mime_type', '<>', 'directory')
                         ->cursor();
            foreach ($files as $file) {
                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                $filePath = "{$baseLocationDir}{$dir->path}/{$file->name}";
                try {
                    $dirPathForFilePartial = "{$location}{$dir->path}";
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
    }

    private function createDirIfNotExists($dirPath)
    {
        if (array_key_exists($dirPath, $this->knownDirectories)) {
            return;
        }

        if (Storage::disk($this->disk)->exists($dirPath)) {
            $this->knownDirectories[$dirPath] = true;
            return;
        }

        Storage::disk($this->disk)->makeDirectory($dirPath);
        $p = Storage::disk($this->disk)->path($dirPath);
        chmod($p, 0777);
        $this->knownDirectories[$dirPath] = true;
    }
}
