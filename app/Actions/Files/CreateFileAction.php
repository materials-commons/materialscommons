<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Jobs\Files\GenerateThumbnailJob;
use App\Models\File;
use App\Models\Script;
use Illuminate\Support\Collection;
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

    public function __invoke($project, $dir, $description, $file, $name = null)
    {
        umask(0);
        $nameToUse = $name ?? $file->getClientOriginalName();
        $checksum = md5_file($file->getRealPath());

        // Check if the exact file already exists
        $existingFile = $this->matchingFileInDir($dir->id, $checksum, $nameToUse);
        if (!is_null($existingFile)) {
            $existing = File::where('directory_id', $dir->id)
                            ->where('name', $existingFile->name)
                            ->whereNull('dataset_id')
                            ->whereNull('deleted_at')
                            ->get();
            if ($existing->count() != 1) {
                File::whereIn('id', $existing->pluck('id'))->update(['current' => false]);

                // We need to refresh the state of $existingFile because it may be different from it was when we
                // first retrieved. If current was true when first retrieved, and it's been set to false above, then
                // the update below will do nothing as laravel will skip doing the update.
                $existingFile->refresh();
            }

            $existingFile->update(['current' => true]);

            if ($existingFile->shouldBeConverted()) {
                ConvertFileJob::dispatch($existingFile)->onQueue('globus');
            }

            if ($existingFile->isImage()) {
                GenerateThumbnailJob::dispatch($existingFile)->onQueue('globus');
            }

            if ($existingFile->isRunnable()) {
                Script::createScriptForFileIfNeeded($existingFile);
            }

            $this->fireTriggers($existingFile);

            return $existingFile;
        }

        $mimeType = mime_content_type($file->getRealPath());

        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => $checksum,
            //            'mime_type'    => $file->getClientMimeType(),
            'mime_type'    => $mimeType,
            'size'         => $file->getSize(),
            'name'         => $nameToUse,
            'owner_id'     => auth()->id(),
            'current'      => true,
            'description'  => $description,
            'project_id' => $project->id,
            'directory_id' => $dir->id,
            'disk'         => 'mcfs',
        ]);

        $existing = File::where('directory_id', $dir->id)
                        ->whereNull('dataset_id')
                        ->whereNull('deleted_at')
                        ->where('name', $fileEntry->name)
                        ->get();
        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)
                                    ->whereNull('deleted_at')
                                    ->first();

        if (!$matchingFileChecksum) {
            // Just save physical file and insert into database
            $this->saveFile($file, $fileEntry->uuid);
        } else {
            // Matching file found, so point at it. At this point the match is either the original file that
            // everything points at or it's a file container the pointer (the pointer is uses_uuid and uses_id are set).
            // So determine which case and set fileEntry uses_uuid and uses_id the appropriate value (which for a pointer
            // is the uses_uuid/uses_id, and if it's the file everything points at its uuid/id).
            $usesUuid = blank($matchingFileChecksum->uses_uuid) ? $matchingFileChecksum->uuid : $matchingFileChecksum->uses_uuid;
            $usesId = blank($matchingFileChecksum->uses_id) ? $matchingFileChecksum->id : $matchingFileChecksum->uses_id;
            $fileEntry->uses_uuid = $usesUuid;
            $fileEntry->uses_id = $usesId;
            if (!$matchingFileChecksum->realFileExists()) {
                $this->saveFile($file, $usesUuid);
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

        if ($fileEntry->isImage()) {
            GenerateThumbnailJob::dispatch($fileEntry)->onQueue('globus');
        }

        if ($fileEntry->isRunnable()) {
            Script::createScriptForFileIfNeeded($fileEntry);
        }

        $this->fireTriggers($fileEntry);

        return $fileEntry;
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

    private function fireTriggers(File $file)
    {
        foreach ($this->triggers as $trigger) {
            if ($trigger->fileWillActivateTrigger($file)) {
                $trigger->execute();
            }
        }
    }

}
