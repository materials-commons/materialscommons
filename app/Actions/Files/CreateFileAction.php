<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Models\File;
use Ramsey\Uuid\Uuid;

class CreateFileAction
{
    use SaveFile;

    public function __invoke($projectId, $directoryId, $description, $file, $name = null)
    {
        umask(0);
        $mimeType = mime_content_type($file->getRealPath());
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($file->getRealPath()),
//            'mime_type'    => $file->getClientMimeType(),
            'mime_type'    => $mimeType,
            'size'         => $file->getSize(),
            'name'         => $name ?? $file->getClientOriginalName(),
            'owner_id'     => auth()->id(),
            'current'      => true,
            'description'  => $description,
            'project_id'   => $projectId,
            'directory_id' => $directoryId,
        ]);

        $existing = File::where('directory_id', $directoryId)->where('name', $fileEntry->name)->get();
        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)->first();

        if (!$matchingFileChecksum) {
            // Just save physical file and insert into database
            $this->saveFile($file, $fileEntry->uuid);
        } else {
            // Matching file found, so point at it. At this point the match is either the original file that
            // everything points at or its a file container the pointer (the pointer is uses_uuid and uses_id are set).
            // So determine which case and set fileEntry uses_uuid and uses_id the appropriate value (which for a pointer
            // is the uses_uuid/uses_id, and if its the file everything points at its uuid/id).
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

        return $fileEntry;
    }

}
