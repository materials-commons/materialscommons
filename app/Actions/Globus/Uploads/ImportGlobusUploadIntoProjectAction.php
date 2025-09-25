<?php

namespace App\Actions\Globus\Uploads;

use App\Enums\GlobusStatus;
use App\Models\GlobusUploadDownload;
use App\Traits\Files\ImportFiles;
use App\Traits\Triggers\FiresTriggers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImportGlobusUploadIntoProjectAction
{
//    use PathForFile;
    use FiresTriggers;
    use ImportFiles;

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

        $isEmpty = empty(Storage::disk('mcfs')->allFiles("__globus_uploads/{$this->globusUpload->uuid}"));
        if ($isEmpty) {
            $this->cleanupAfterProcessingAllFiles();
        }
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
        // Sometimes path is blank. When this happens, just return true, so that this globus request gets cleaned up.
        if (blank($this->globusUpload->path)) {
            return true;
        }

        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->globusUpload->path),
                                                     RecursiveIteratorIterator::SELF_FIRST);
        $fileCount = 0;

        $location = "__globus_uploads/{$this->globusUpload->uuid}";
        $this->globusUpload->load('project');
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
                $this->processDir($path, "mcfs", $location, $this->globusUpload->project,
                                  $this->globusUpload->owner_id);
            } else {
                $file = $this->processFile($path, "mcfs", $location, $this->globusUpload->project,
                                           $this->globusUpload->owner_id, $finfo);
                if (is_null($file)) {
                    // processing file failed, so stop let job be processed later
                    $currentErrors = $this->globusUpload->errors ?? 0;
                    $this->globusUpload->update([
                                                    'status' => GlobusStatus::Done,
                                                    'errors' => $currentErrors + 1,
                                                ]);
                    return false;
                }
//                $this->trackForTriggers($file);
                $fileCount++;
            }
        }

        $this->globusUpload->load('owner');
//        $this->fireTriggers($this->globusUpload->owner);

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

    // Cleanup
    private function cleanupAfterProcessingAllFiles()
    {
        $this->globusUpload->delete();
        Storage::disk('mcfs')->deleteDirectory("__globus_uploads/{$this->globusUpload->uuid}");
    }
}
