<?php

namespace App\Traits\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use function basename;
use function blank;
use function chmod;
use function dirname;
use function is_null;
use function md5_file;
use function mime_content_type;
use function optional;
use function unlink;

trait ImportFiles
{
    private function processDir($path, $disk, $location, $projectId, $ownerId): File
    {
        $pathPart = Storage::disk($disk)->path($location);
        $dirPath = Str::replaceFirst($pathPart, "", $path);
        if (blank($dirPath)) {
            $dirPath = "/";
        }
        $parentDir = File::where('project_id', $projectId)
                         ->whereNull('dataset_id')
                         ->whereNull('deleted_at')
                         ->where('path', dirname($dirPath))
                         ->where('current', true)
                         ->first();
        $dir = File::where('project_id', $projectId)
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
            'owner_id'     => $ownerId,
            'project_id'   => $projectId,
            'current'      => true,
            'directory_id' => optional($parentDir)->id,
        ]);
    }

    private function processFile($path, $disk, $location, $projectId, $ownerId, \SplFileInfo $finfo, $attachTo = null)
    {
        // Find or create directory file is in
        $currentDir = $this->processDir(dirname($path), $disk, $location, $projectId, $ownerId);
        $finfo->getSize();
        mime_content_type($path);
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($path),
            'mime_type'    => mime_content_type($path),
            'size'         => $finfo->getSize(),
            'name'         => $finfo->getFilename(),
            'owner_id'     => $ownerId,
            'current'      => true,
            'is_deleted'   => false,
            'description'  => "",
            'project_id'   => $projectId,
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

        if (!is_null($attachTo)) {
            $attachTo->files()->attach($fileEntry);
        }

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
            $uuid = $file->getFileUuidToUse();
            $to = Storage::disk('mcfs')->path($file->realPathPartial());

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

    private function getProjectDirPath($disk, $location, $path): string
    {
        $pathPart = Storage::disk($disk)->path($location);
        $dirPath = Str::replaceFirst($pathPart, "", $path);
        if (blank($dirPath)) {
            $dirPath = "/";
        }

        return $dirPath;
    }
}