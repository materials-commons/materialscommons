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

class LoadGlobusUploadIntoProjectAction
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

        // Start off with root dir and then adjust as needed
        $currentDir = File::where('project_id', $this->globusUpload->project_id)
                          ->where('name', '/')
                          ->first();
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
                // assign current dir here
                $currentDir = $this->processDir($path, $currentDir);
            } else {
                if (is_null($this->processFile($path, $finfo, $currentDir))) {
                    // processing file failed, so stop let job be processed later
                    $this->globusUpload->update(['status' => GlobusStatus::Done]);
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

    private function processDir($path, File $currentDir): File
    {
        $pathPart = Storage::disk('mcfs')->path("__globus_uploads/{$this->globusUpload->uuid}");
        $dirPath = Str::replaceFirst($pathPart, "", $path);
        $parentDir = File::where('project_id', $this->globusUpload->project_id)->where('path',
            dirname($dirPath))->first();
        $dir = File::where('project_id', $this->globusUpload->project_id)->where('path', $dirPath)->first();
        if ($dir !== null) {
            return $dir;
        }

        return File::create([
            'name'         => basename($dirPath),
            'path'         => $dirPath,
            'mime_type'    => 'directory',
            'owner_id'     => $this->globusUpload->owner->id,
            'project_id'   => $this->globusUpload->project->id,
            'directory_id' => $parentDir->id,
        ]);
    }

    private function processFile($path, \SplFileInfo $finfo, File $currentDir)
    {
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
            'description'  => "",
            'project_id'   => $this->globusUpload->project->id,
            'directory_id' => $currentDir->id,
        ]);

        $existing = File::where('directory_id', $currentDir->id)->where('name', $fileEntry->name)->get();
        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)->whereNull('uses_id')->first();

        if (!$matchingFileChecksum) {
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
            try {
                if (!unlink($path)) {
                    echo "unlink failed\n";
                }
            } catch (\Exception $e) {
                // unlink through an exception
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
