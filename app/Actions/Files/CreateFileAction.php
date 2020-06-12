<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Models\File;
use Ramsey\Uuid\Uuid;

class CreateFileAction
{
    use SaveFile;

    public function __invoke($projectId, $directoryId, $description, $file)
    {
        umask(0);
        $mimeType = mime_content_type($file->getRealPath());
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($file->getRealPath()),
//            'mime_type'    => $file->getClientMimeType(),
            'mime_type'    => $mimeType,
            'size'         => $file->getSize(),
            'name'         => $file->getClientOriginalName(),
            'owner_id'     => auth()->id(),
            'current'      => true,
            'description'  => $description,
            'project_id'   => $projectId,
            'directory_id' => $directoryId,
        ]);

        $existing = File::where('directory_id', $directoryId)->where('name', $fileEntry->name)->get();
        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)->whereNull('uses_id')->first();

        if (!$matchingFileChecksum) {
            // Just save physical file and insert into database
            $this->saveFile($file, $fileEntry->uuid);
        } else {
            // Matching file found, so point at it.
            $fileEntry->uses_uuid = $matchingFileChecksum->uuid;
            $fileEntry->uses_id = $matchingFileChecksum->id;
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
