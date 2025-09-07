<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Jobs\Files\GenerateThumbnailJob;
use App\Models\File;
use App\Models\Script;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class CreateFileAction
{
    use SaveFile;

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

        // Check if the current file exists on disk, and if it doesn't,
        // then mark it as fixed and save it.
        if (!$existingFile->realFileExists()) {
            $this->setFileHealthFixed($existingFile, $source);

            // Identify which uuid to save the file to.
            $useUuid = blank($existingFile->uses_uuid) ? $existingFile->uuid : $existingFile->uses_uuid;

            $this->saveFile($file, $useUuid);
        }

        // Run any triggers and background jobs that may be associated with the file. These may
        // have been run in the past, but since we don't know their status, we kick them off,
        // and they can figure out the state.
        $this->runTriggersAndBackgroundJobs($existingFile);

        // One last paranoid check to make sure the file exists on disk. It's possible that
        // the save above failed, perhaps even silently.
        if (!$existingFile->realFileExists()) {
            $this->setFileHealthMissing($existingFile, $source);
            Log::error("File {$existingFile->name}/{$existingFile->id} does not exist after save");
        }

        return $existingFile;
    }

    // handleUploadOfNewFile uploads a new file that does not exist in the directory.
    // This routine does a bunch of tests to see if the file exists on disk, and if not,
    // it marks the file as fixed and saves it. It also marks all other files in the directory
    // as not current. Finally, it does a file sanity check that the file actually exists after
    // saving it.
    public function handleUploadOfNewFile($file, bool|string $checksum, mixed $nameToUse, $description, $project, $dir,
        $source): File
    {
        $mimeType = mime_content_type($file->getRealPath());

        // Check if there is already a file with the same checksum in the system.
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


        if (!$matchingFileByChecksum) {
            // If there is not file with a matching checksum, then this is a completely
            // new file. We save the file to the generated UUID.
            $this->saveFile($file, $fileEntry->uuid);
        } else {
            // Matching file found, so point at it. At this point the match is either the original file that
            // everything points at or it's a file containing the pointer to the original file
            // (the pointer is uses_uuid and uses_id are set).
            //
            // So determine which case and set fileEntry uses_uuid and uses_id the appropriate value (which for a pointer
            // is the uses_uuid/uses_id, and if it's the file, everything points at its uuid/id).
            $usesUuid = blank($matchingFileByChecksum->uses_uuid) ? $matchingFileByChecksum->uuid : $matchingFileByChecksum->uses_uuid;
            $usesId = blank($matchingFileByChecksum->uses_id) ? $matchingFileByChecksum->id : $matchingFileByChecksum->uses_id;

            // Set the fileEntry to point to the uses_uuid/uses_id.
            $fileEntry->uses_uuid = $usesUuid;
            $fileEntry->uses_id = $usesId;

            // Check if the current file exists on disk, and if it doesn't, then save it and mark it as fixed.
            if (!$matchingFileByChecksum->realFileExists()) {
                // The matching file does not exist on disk, so save it and mark it as fixed.
                $this->setFileHealthFixed($matchingFileByChecksum, $source);
                $this->saveFile($file, $usesUuid);
            }
        }

        // At this point we've saved the file to disk, so lets save the fileEntry to the database.
        $fileEntry->save();
        $fileEntry->refresh();

        // Mark all files in the current directory matching this file as not current
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
            $this->setFileHealthMissing($fileEntry, $source);
            // If matchFileByChecksum is not null, then that file is also missing, as that is what we
            // saved to. So mark that file as missing as well. There may be other files that are missing
            // that point to the uuid/uses_uuid. These will be caught when the health check is run.
            $this->setFileHealthMissing($matchingFileByChecksum, $source);
            Log::error("File {$fileEntry->name}/{$fileEntry->id} does not exist after save");
        }

        return $fileEntry;
    }

    private function setFileHealthMissing($file, $source): void
    {
        $file->update([
            'health'                     => 'missing',
            'file_missing_at'            => now(),
            'file_missing_determined_by' => 'create-file-action:existence-check',
            'health_fixed_at'            => null,
            'health_fixed_by'            => null,
            'upload_source'              => $source,
        ]);
    }

    private function setFileHealthFixed($file, $source): void
    {
        $file->update([
            'health_fixed_at'            => now(),
            'health_fixed_by'            => 'create-file-action:existence-check',
            'file_missing_at'            => null,
            'file_missing_determined_by' => null,
            'health'                     => 'fixed',
            'upload_source'              => $source,
        ]);
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
