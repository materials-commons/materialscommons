<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Jobs\Files\GenerateThumbnailJob;
use App\Models\File;
use App\Models\Script;
use App\Traits\Files\FileHealth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class CreateFileAction
{
    use SaveFile;
    use FileHealth;

    private Collection $triggers;

    function __construct(?Collection $triggers = null)
    {
        if (is_null($triggers)) {
            $this->triggers = collect();
        } else {
            $this->triggers = $triggers;
        }
    }

    public function __invoke($project, $dir, $description, $file, $source, $name = null)
    {
        umask(0);
        $nameToUse = $name ?? $file->getClientOriginalName();
        $checksum = md5_file($file->getRealPath());

        // Check if the exact file already exists
        $existingFile = $this->matchingFileInDir($dir->id, $checksum, $nameToUse);
        if (!is_null($existingFile)) {
            return $this->handleUploadOfExistingFile($dir, $existingFile, $source, $file);
        }

        return $this->handleUploadOfNewFile($file, $checksum, $nameToUse, $description, $project, $dir, $source);
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

    // handleUploadOfExistingFile uploads a file that already exists in the directory.
    // This routine does a bunch of tests to see if the file exists on disk, and if not,
    // it marks the file as fixed and saves it. It also marks all other files in the directory
    // as not current. Finally, it does a file sanity check that the file actually exists after
    // saving it.
    public function handleUploadOfExistingFile($dir, File $existingFile, $source, $file): object
    {
        // Check if there are any other files with the same name in the same directory so we
        // can mark them as not current
        $existing = File::where('directory_id', $dir->id)
                        ->where('name', $existingFile->name)
                        ->whereNull('dataset_id')
                        ->whereNull('deleted_at')
                        ->get();

        if ($existing->count() != 1) {
            File::whereIn('id', $existing->pluck('id'))->update(['current' => false]);

            // We need to refresh the state of $existingFile because it may be different from when it was first
            // retrieved. If current was true when first retrieved, and it's been set to false above, then the
            // update below will do nothing as laravel will skip doing the update.
            $existingFile->refresh();
        }

        // Mark the existing file as current
        $existingFile->update(['current' => true]);

        // There are two cases here we need to account for:
        // 1. The existingFile file is not on disk. In this case we need to mark it as fixed and save it.
        // 2. The existingFile is on disk, in that case we don't need to save anything to disk.

        // Check if the current file exists on disk, and if it doesn't,
        // then mark it as fixed and save it. This is case (1) above.
        if (!$existingFile->realFileExists()) {
            $this->setFileHealthFixed($existingFile, $source);

            // Identify which uuid to save the file to.
            $useUuid = blank($existingFile->uses_uuid) ? $existingFile->uuid : $existingFile->uses_uuid;

            $this->saveFile($file, $useUuid);
        }
        // else $existingFile->realFileExists() is true, so we don't need to do anything. This is case (2) above.

        // Run any triggers and background jobs that may be associated with the file. These may
        // have been run in the past, but since we don't know their status, we kick them off,
        // and they can figure out the state.
        $this->runTriggersAndBackgroundJobs($existingFile);

        // One last paranoid check to make sure the file exists on disk. It's possible that
        // the save above failed, perhaps even silently.
        if (!$existingFile->realFileExists()) {
            $this->setFileHealthMissing($existingFile, 'create-file-action:existence-check', $source);
            Log::error("File {$existingFile->name}/{$existingFile->id} does not exist after save");
        }

        return $existingFile;
    }

    // handleUploadOfNewFile uploads a new file that does not exist in the directory.
    // This routine does a bunch of tests to see if the file exists on disk, and if not,
    // it marks the file as fixed and saves it. It also marks all other files with the
    // same name in the directory as not current. Finally, it does a file sanity check
    // that the file actually exists after saving it.
    public function handleUploadOfNewFile($file, bool|string $checksum, mixed $nameToUse, $description, $project, $dir,
        $source): File
    {
        $mimeType = mime_content_type($file->getRealPath());

        // Check if there is already a file with the same checksum anywhere in the system.
        $matchingFileByChecksum = File::where('checksum', $checksum)
                                      ->whereNull('deleted_at')
                                      ->whereNull('dataset_id')
                                      ->first();

        $fileEntry = new File([
            'uuid'          => Uuid::uuid4()->toString(),
            'checksum'      => $checksum,
            //            'mime_type'    => $file->getClientMimeType(),
            'mime_type'     => $mimeType,
            'size'          => $file->getSize(),
            'name'          => $nameToUse,
            'owner_id'      => auth()->id(),
            'current'       => true,
            'description'   => $description,
            'project_id'    => $project->id,
            'directory_id'  => $dir->id,
            'disk'          => 'mcfs',
            'upload_source' => $source,
            'health'        => 'good',
        ]);

        // A review of the logic for this part of the code. There are 3 cases:
        //
        // 1. We did not find a matching file with the same checksum. In that case we need to save the file
        //    to disk using the UUID for the fileEntry we just created.
        // 2. We found a matching file with the same checksum, but that file does not exist on disk. In that
        //    case we need to save the file to disk to the appropriate UUID for the matchingFileByChecksum
        //    entry and mark the matching file as fixed.
        // 3. We found a matching file with the same checksum, and that file exists on disk. In that case
        //    we don't need to save anything to disk, because we already have a file with the same checksum.

        if (!$matchingFileByChecksum) {
            // If there is not a file with a matching checksum, then this is a completely
            // new file. We save the file to the generated UUID.
            //
            // This is case (1) above.
            $this->saveFile($file, $fileEntry->uuid);
        } else {
            // There was a matching file found, so we want to point at it setting this file's uses_uuid to point
            // at the matching file. At this point the match is either the original file that everything points
            // at or it's a file containing the pointer to the original file (the pointer is uses_uuid).

            // We need to determine which case and set fileEntry uses_uuid and uses_id to the appropriate value.
            $usesUuid = blank($matchingFileByChecksum->uses_uuid) ? $matchingFileByChecksum->uuid : $matchingFileByChecksum->uses_uuid;
            $usesId = blank($matchingFileByChecksum->uses_id) ? $matchingFileByChecksum->id : $matchingFileByChecksum->uses_id;

            // Set the fileEntry to point to the uses_uuid/uses_id.
            $fileEntry->uses_uuid = $usesUuid;
            $fileEntry->uses_id = $usesId;

            // Check if the file exists on disk, and if it doesn't, save it and mark it as fixed.
            if (!$matchingFileByChecksum->realFileExists()) {
                // The matching file does not exist on disk, so save it and mark it as fixed.
                // This is case (2) above
                //
                // There is nothing we need to do with the fileEntry here, because its health is already set to 'good'.
                // We only need to set the health of the matching file to 'fixed'.
                $this->setFileHealthFixed($matchingFileByChecksum, 'create-file-action:existence-check', $source);

                $this->saveFile($file, $usesUuid);
            }
            // else if matchingFileByChecksum->realFileExists() is true, then we don't need to do anything.
            // This is case (3) above.
        }

        // At this point the file exists on disk. Let's create the database entry and refresh our state.
        $fileEntry->save();
        $fileEntry->refresh();

        // Mark all files in the current directory matching this file's name as not current
        File::where('directory_id', $dir->id)
            ->whereNull('dataset_id')
            ->whereNull('deleted_at')
            ->where('id', '<>', $fileEntry->id)
            ->where('name', $fileEntry->name)
            ->update(['current' => false]);

        // Run any triggers and background jobs that may be associated with the file.
        $this->runTriggersAndBackgroundJobs($fileEntry);

        // One last paranoid check to make sure the file exists on disk. It's possible that
        // the save above failed, perhaps even silently.
        if (!$fileEntry->realFileExists()) {
            $this->setFileHealthMissing($fileEntry, 'create-file-action:existence-check', $source);
            // If matchFileByChecksum is not null, then that file is also missing, as that is what we
            // saved to. So mark that file as missing as well. There may be other files that are missing
            // that point to the uuid/uses_uuid. These will be caught when the health check is run.
            $this->setFileHealthMissing($matchingFileByChecksum, 'create-file-action:existence-check', $source);
            Log::error("File {$fileEntry->name}/{$fileEntry->id} does not exist after save");
        }

        return $fileEntry;
    }



    private function runTriggersAndBackgroundJobs($file): void
    {
        if ($file->shouldBeConverted()) {
            ConvertFileJob::dispatch($file)->onQueue('globus');
        }

        if ($file->isImage()) {
            GenerateThumbnailJob::dispatch($file)->onQueue('globus');
        }

        if ($file->isRunnable()) {
            Script::createScriptForFileIfNeeded($file);
        }

//        $this->fireTriggers($fileEntry);
    }

    private function fireTriggers(File $file)
    {
        foreach ($this->triggers as $trigger) {
            if ($trigger->fileWillActivateTrigger($file)) {
                $trigger->execute();
            }
        }
    }

}
