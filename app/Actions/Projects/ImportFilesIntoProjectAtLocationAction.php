<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Models\User;
use App\Traits\Files\ImportFiles;
use App\Traits\Triggers\FiresTriggers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function is_null;

class ImportFilesIntoProjectAtLocationAction
{
    use FiresTriggers;
    use ImportFiles;

    private Project $project;
    private User $owner;
    private string $disk;
    private string $location;

    public function __construct()
    {
        $this->__constructFireTriggers();
    }

    public function execute(Project $project, $disk, $location, User $owner, $attachTo = null)
    {
        $this->project = $project;
        $this->disk = $disk;
        $this->location = $location;
        $this->owner = $owner;

        if (!$this->importFiles($attachTo)) {
            // Did not complete importing uploaded files
            return;
        }

        $this->cleanupAfterProcessingAllFiles($this->disk, $this->location);
    }

    private function importFiles($attachTo): bool
    {
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Storage::disk($this->disk)->path($this->location)),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($dirIterator as $path => $finfo) {
            // Don't process entries that end with /. or /..
            if (Str::endsWith($path, "/.") || Str::endsWith($path, "/..")) {
                continue;
            }

            if ($finfo->isDir()) {
                $this->processDir($path, $this->disk, $this->location, $this->project, $this->owner->id);
            } else {
                $file = $this->processFile($path,
                    $this->disk,
                    $this->location,
                    $this->project,
                    $this->owner->id,
                    $finfo,
                    $attachTo);
                if (is_null($file)) {
                    // processing file failed, so stop let job be processed later
                    return false;
                }
                $this->trackForTriggers($file);
            }
        }

        $this->fireTriggers($this->owner);

        return true;
    }

    private function cleanupAfterProcessingAllFiles($disk, $location): void
    {
        Storage::disk($disk)->deleteDirectory($location);
    }
}