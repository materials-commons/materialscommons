<?php

namespace App\Actions\Globus\Uploads;

use App\Enums\GlobusStatus;
use App\Jobs\Files\ConvertFileJob;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImportGlobusUploadIntoProjectAction
{
    use PathForFile;

    /** @var \App\Models\GlobusUploadDownload */
    private $globusUpload;

    private $maxItemsToProcess;

    /** @var \App\Actions\Globus\GlobusApi */
    private $globusApi;

    public function __construct(GlobusUploadDownload $globusUpload, $maxItemsToProcess, $globusApi)
    {
        umask(0);
        $this->globusUpload = $globusUpload;
        $this->maxItemsToProcess = $maxItemsToProcess;
        $this->globusApi = $globusApi;
    }

    public function __invoke()
    {
        $this->removeAclIfExists();

        if (!$this->importUploadedFiles()) {
            // Did not complete importing uploaded files
            return;
        }

        $this->cleanupAfterProcessingAllFiles();
    }

    // ACL Handling

    private function removeAclIfExists()
    {
        if ($this->aclAlreadyRemoved()) {
            return;
        }

        try {
            $this->globusApi->deleteEndpointAclRule($this->globusUpload->globus_endpoint_id,
                $this->globusUpload->globus_acl_id);
            $this->markAclAsRemoved();
        } catch (\Exception $e) {
            // do nothing
        }
    }

    private function aclAlreadyRemoved()
    {
        return is_null($this->globusUpload->globus_acl_id);
    }

    private function markAclAsRemoved()
    {
        $this->globusUpload->update(['globus_acl_id' => null]);
    }

    // File and Directory Importing

    private function importUploadedFiles()
    {
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->globusUpload->path),
            RecursiveIteratorIterator::SELF_FIRST);
        $fileCount = 0;

        foreach ($dirIterator as $path => $finfo) {
            if ($this->maxItemsProcessed($fileCount)) {
                // Mark job as done so that it will be picked up again and processed. This way
                // we break a large upload into chunks.
                $this->globusUpload->update(['status' => GlobusStatus::Done]);
                return false;
            }

            // Don't process entries that end with /. or /..
            if (Str::endsWith($path, "/.") || Str::endsWith($path, "/..")) {
                continue;
            }

            if ($finfo->isDir()) {
                $this->processDir($path);
            } else {
                if (is_null($this->processFile($path, $finfo))) {
                    // processing file failed, so stop let job be processed later
                    $currentErrors = $this->globusUpload->errors ?? 0;
                    $this->globusUpload->update([
                        'status' => GlobusStatus::Done,
                        'errors' => $currentErrors + 1,
                    ]);
                    return false;
                }
                $fileCount++;
            }
        }

        return true;
    }

    private function maxItemsProcessed($fileCount)
    {
        // if maxItemsToProcess less than one then there is no limit
        if ($this->maxItemsToProcess < 1) {
            return false;
        }

        return $fileCount >= $this->maxItemsToProcess;
    }

    // Look up or create directory
    private function processDir($path): File
    {
        $pathPart = Storage::disk('mcfs')->path("__globus_uploads/{$this->globusUpload->uuid}");
        $dirPath = Str::replaceFirst($pathPart, "", $path);
        if (blank($dirPath)) {
            $dirPath = "/";
        }
        $parentDir = File::where('project_id', $this->globusUpload->project_id)
                         ->whereNull('dataset_id')
                         ->whereNull('deleted_at')
                         ->where('path', dirname($dirPath))
                         ->where('current', true)
                         ->first();
        $dir = File::where('project_id', $this->globusUpload->project_id)
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
            'owner_id'     => $this->globusUpload->owner->id,
            'project_id'   => $this->globusUpload->project->id,
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
            'owner_id'     => $this->globusUpload->owner->id,
            'current'      => true,
            'is_deleted'   => false,
            'description'  => "",
            'project_id'   => $this->globusUpload->project->id,
            'directory_id' => $currentDir->id,
        ]);

        $existing = File::where('directory_id', $currentDir->id)->where('name', $fileEntry->name)->get();
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

    private function moveFileIntoProject($path, $file)
    {
        try {
            $uuid = $this->getUuid($file);
            $to = $this->getDirPathForFile($file)."/{$uuid}";
            $pathPart = Storage::disk('mcfs')->path("__globus_uploads");
            $filePath = Str::replaceFirst($pathPart, "__globus_uploads", $path);

            if (Storage::disk('mcfs')->move($filePath, $to) !== true) {
                $status = Storage::disk('mcfs')->copy($filePath, $to);
                $fpath = Storage::disk('mcfs')->path($to);
                chmod($fpath, 0777);
                unlink($path);

                return $status;
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return false;
        }

        return true;
    }

    // Cleanup

    private function cleanupAfterProcessingAllFiles()
    {
        $this->globusUpload->delete();
        Storage::disk('mcfs')->deleteDirectory("__globus_uploads/{$this->globusUpload->uuid}");
    }
}
