<?php

namespace App\Traits\Files;

use App\Models\File;
use App\Traits\CreateDirectories;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use function blank;
use function chmod;
use function dirname;
use function is_null;
use function md5_file;
use function mime_content_type;
use function unlink;

trait ImportFiles
{
    use CreateDirectories;
    use FileHealth;
    use ConvertFile;

    private function processDir($path, $disk, $location, $project, $ownerId): File
    {
        $pathPart = Storage::disk($disk)->path($location);
        $dirPath = Str::replaceFirst($pathPart, "", $path);
        if (blank($dirPath)) {
            $dirPath = "/";
        }
        $parentDir = File::where('project_id', $project->id)
                         ->whereNull('dataset_id')
                         ->whereNull('deleted_at')
                         ->where('path', dirname($dirPath))
                         ->where('mime_type', 'directory')
                         ->where('current', true)
                         ->first();
        return $this->getOrCreateSingleDirectoryIfDoesNotExist($parentDir, $dirPath, $project, $ownerId);
    }

    private function processFile($path, $disk, $location, $project, $ownerId, \SplFileInfo $finfo, $attachTo = null)
    {
        // Find or create the directory the file is in
        $currentDir = $this->processDir(dirname($path), $disk, $location, $project, $ownerId);

        $checksum = md5_file($path);
        $file = $this->matchingFileInDir($currentDir->id, $checksum, $finfo->getFilename());
        if (!is_null($file)) {
            $f = $this->handleProcessingOfExistingFile($file, $currentDir, $path);
        } else {
            $f = $this->handleProcessingOfNewFile($currentDir, $project, $ownerId, $path, $finfo);
        }

        if (is_null($f)) {
            return null;
        }

        if (!is_null($attachTo)) {
            $attachTo->files()->attach($f);
        }

        return $f;
    }

    private function matchingFileInDir($directoryId, $checksum, $name)
    {
        return File::where('checksum', $checksum)
                   ->where('directory_id', $directoryId)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->where('name', $name)
                   ->first();
    }

    private function handleProcessingOfExistingFile($file, $dir, $path)
    {
        if (!$file->realFileExists()) {
            if (!$this->moveFileIntoProject($path, $file)) {
                Log::error("Move failed for matchingFile in globus: {$file->realPathPartial()}");
                $this->setFileHealthMissing($file, 'globus-import-files:move-file-into-project', 'globus');
                return null;
            } else {
                $this->setFileHealthFixed($file, 'globus-import-files:move-file-into-project', 'globus');
            }
        }

        // If we are here, the file should exist, and we can set current flags
        // Check if there are any other files with the same name in the same
        // directory, so we can mark them as not current.
        File::where('directory_id', $dir->id)
            ->where('id', '<>', $file->id)
            ->where('name', $file->name)
            ->whereNull('dataset_id')
            ->whereNull('deleted_at')
            ->update(['current' => false]);;

        // Mark the existing file as current
        $file->update(['current' => true]);
        return $file;
    }

    private function handleProcessingOfNewFile($currentDir, $project, $ownerId, $path, $finfo): ?File
    {
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($path),
            'mime_type'    => mime_content_type($path),
            'size'         => $finfo->getSize(),
            'name'         => $finfo->getFilename(),
            'owner_id'     => $ownerId,
            'current'      => true,
            'description'  => "",
            'project_id'   => $project->id,
            'directory_id' => $currentDir->id,
        ]);

        $matchingFileByChecksum = File::where('checksum', $fileEntry->checksum)
                                      ->whereNull('deleted_at')
                                      ->whereNull('dataset_id')
                                      ->first();

        if (is_null($matchingFileByChecksum)) {
            // Just save physical file and insert into the database
            if (!$this->moveFileIntoProject($path, $fileEntry)) {
                return null;
            }
        } else {
            // Matching file found, so point at it and remove the uploaded file on disk. If the uploaded
            // file isn't removed, then it could be processed a second time if the first run doesn't complete
            // processing of all files.
            $fileEntry->uses_uuid = $matchingFileByChecksum->getFileUuidToUse();
            if (!$matchingFileByChecksum->realFileExists()) {
                if (!$this->moveFileIntoProject($path, $matchingFileByChecksum)) {
                    Log::error("Move failed for matchingFile in globus: {$matchingFileByChecksum->realPathPartial()}");
                    $this->setFileHealthMissing($matchingFileByChecksum, 'globus-import-files:move-file-into-project', 'globus');
                    return null;
                } else {
                    $this->setFileHealthFixed($matchingFileByChecksum, 'globus-import-files:move-file-into-project', 'globus');
                }
            }
            try {
                // If we are here, then the matching file should exist, and we can remove the uploaded file
                if (!$matchingFileByChecksum->realFileExists()) {
                    Log::error("File not found for matchingFile after move in globus: {$matchingFileByChecksum->realPathPartial()}");
                    $this->setFileHealthMissing($matchingFileByChecksum, 'globus-import-files:move-file-into-project', 'globus');
                    return null;
                }
                if (!unlink($path)) {
                    return null;
                }
            } catch (\Exception $e) {
                // unlink threw an exception
                $msg = $e->getMessage();
                Log::error("ImportFiles: Error removing file {$path}: {$msg}");
                return null;
            }
        }

        // One last sanity check on file existing
        if (!$fileEntry->realFileExists()) {
            Log::error("File not found after save in globus: {$fileEntry->realPathPartial()}");
            return null;
        }

        $fileEntry->save();
        $fileEntry->refresh();

        // Mark all files matching this file's name in $currentDir as not current
        File::where('directory_id', $currentDir->id)
            ->whereNull('dataset_id')
            ->whereNull('deleted_at')
            ->where('id', '<>', $fileEntry->id)
            ->where('name', $fileEntry->name)
            ->update(['current' => false]);

        $this->runTriggersAndBackgroundJobs($fileEntry);

        return $fileEntry;
    }

    private function moveFileIntoProject($from, $file): bool
    {
        try {
            $to = Storage::disk('mcfs')->path($file->realPathPartial());

            $dirpath = dirname($to);
            \File::ensureDirectoryExists($dirpath);

            if (!\File::move($from, $to)) {
                if (!\File::copy($from, $to)) {
                    return false;
                }

                if (!$file->realFileExists()) {
                    Log::log('error', "File not found after copy in globus: {$file->realPathPartial()}");
                    return false;
                }
                \File::delete($from);
            }

            chmod($to, 0777);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            Log::log('error', "Error moving or copying file {$file->id} from {$from} to {$to}: {$msg}");
            return false;
        }

        // If we are here, then the file should have been moved successfully
        if (!$file->realFileExists()) {
            Log::log('error', "File {$file->id} not found after copy/move: {$file->realPathPartial()}");
            return false;
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
