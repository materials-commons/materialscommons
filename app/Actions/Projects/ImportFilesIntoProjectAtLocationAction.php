<?php

namespace App\Actions\Projects;

use App\Enums\GlobusStatus;
use App\Jobs\Files\ConvertFileJob;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function basename;
use function blank;
use function chmod;
use function dirname;
use function is_null;
use function md5_file;
use function mime_content_type;
use function optional;
use function unlink;

class ImportFilesIntoProjectAtLocationAction
{
    use PathForFile;

    private Project $project;
    private User $owner;
    private string $disk;
    private string $location;

    public function execute(Project $project, $disk, $location, User $owner)
    {
        $this->project = $project;
        $this->disk = $disk;
        $this->location = $location;
        $this->owner = $owner;

        if (!$this->importUploadedFiles()) {
            // Did not complete importing uploaded files
            return;
        }

        $this->cleanupAfterProcessingAllFiles();
    }

    private function importUploadedFiles()
    {
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Storage::disk($this->disk)->path($this->location)),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($dirIterator as $path => $finfo) {
            // Don't process entries that end with /. or /..
            if (Str::endsWith($path, "/.") || Str::endsWith($path, "/..")) {
                continue;
            }

            if ($finfo->isDir()) {
                $this->processDir($path);
            } else {
                if (is_null($this->processFile($path, $finfo))) {
                    // processing file failed, so stop let job be processed later
                    return false;
                }
            }
        }

        return true;
    }

    // Look up or create directory
    private function processDir($path): File
    {
        $pathPart = Storage::disk($this->disk)->path($this->location);
        $dirPath = Str::replaceFirst($pathPart, "", $path);
        if (blank($dirPath)) {
            $dirPath = "/";
        }
        $parentDir = File::where('project_id', $this->project->id)
                         ->whereNull('dataset_id')
                         ->whereNull('deleted_at')
                         ->where('path', dirname($dirPath))
                         ->where('current', true)
                         ->first();
        $dir = File::where('project_id', $this->project->id)
                   ->where('path', $dirPath)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
        if ($dir !== null) {
            return $dir;
        }

        return File::create([
            'name'         => basename($dirPath),
            'path'         => $dirPath,
            'mime_type'    => 'directory',
            'owner_id'     => $this->owner->id,
            'project_id'   => $this->project->id,
            'current'      => true,
            'directory_id' => optional($parentDir)->id,
        ]);
    }

    private function processFile($path, \SplFileInfo $finfo)
    {
        // Find or create directory file is in
        $currentDir = $this->processDir(dirname($path));
        $finfo->getSize();
        mime_content_type($path);
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($path),
            'mime_type'    => mime_content_type($path),
            'size'         => $finfo->getSize(),
            'name'         => $finfo->getFilename(),
            'owner_id'     => $this->owner->id,
            'current'      => true,
            'is_deleted'   => false,
            'description'  => "",
            'project_id'   => $this->project->id,
            'directory_id' => $currentDir->id,
        ]);

        $existing = File::where('directory_id', $currentDir->id)
                        ->where('name', $fileEntry->name)
                        ->get();

        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)
                                    ->whereNull('deleted_at')
                                    ->whereNull('dataset_id')
                                    ->whereNull('uses_uuid')
                                    ->first();

        if (is_null($matchingFileChecksum)) {
            // Just save physical file and insert into database
            if (!$this->moveFileIntoProject($path, $fileEntry)) {
                return null;
            }
        } else {
            // Matching file found, so point at it and remove the uploaded file on disk. If the uploaded
            // file isn't removed then it could be processed a second time if the first run doesn't complete
            // processing of all files.
            $fileEntry->uses_uuid = $matchingFileChecksum->uuid;
            $fileEntry->uses_id = $matchingFileChecksum->id;
            if (!$matchingFileChecksum->realFileExists()) {
                if (!$this->moveFileIntoProject($path, $matchingFileChecksum)) {
                    return null;
                }
            }
            try {
                if (!unlink($path)) {
                    return null;
                }
            } catch (\Exception $e) {
                // unlink threw an exception
                $msg = $e->getMessage();
                return null;
            }
        }

        $fileEntry->save();

        if ($existing->isNotEmpty()) {
            // Existing files to mark as not current
            File::whereIn('id', $existing->pluck('id'))->update(['current' => false]);
        }

        if ($fileEntry->shouldBeConverted()) {
            ConvertFileJob::dispatch($fileEntry)->onQueue('globus');
        }

        return $fileEntry;
    }

    private function moveFileIntoProject($from, $file): bool
    {
        try {
            $uuid = $this->getUuid($file);
            $to = Storage::disk('mcfs')->path($this->getDirPathForFile($file)."/{$uuid}");

            $dirpath = dirname($to);
            \File::ensureDirectoryExists($dirpath);

            if (!\File::move($from, $to)) {
                if (!\File::copy($from, $to)) {
                    return false;
                }

                \File::delete($from);
            }

            chmod($to, 0777);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return true;
        }

        return true;
    }

    private function cleanupAfterProcessingAllFiles(): void
    {
        Storage::disk($this->disk)->deleteDirectory($this->location);
    }
}