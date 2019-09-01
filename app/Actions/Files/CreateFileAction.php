<?php

namespace App\Actions\Files;

use App\Models\File;
use Ramsey\Uuid\Uuid;

class CreateFileAction
{
    use SaveFile;

    public function __invoke($projectId, $directoryId, $file)
    {
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($file->getRealPath()),
            'mime_type'    => $file->getClientMimeType(),
            'size'         => $file->getClientSize(),
            'name'         => $file->getClientOriginalName(),
            'owner_id'     => auth()->id(),
            'current'      => true,
            'project_id'   => $projectId,
            'directory_id' => $directoryId,
        ]);

        $existing             = File::where('directory_id', $directoryId)->where('name', $fileEntry->name)->get();
        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)->whereNull('uses_id')->first();

        if ( ! $matchingFileChecksum) {
            // Just save physical file and insert into database
            $this->saveFile($file, $fileEntry->uuid);
        } else {
            $fileEntry->uses_uuid = $matchingFileChecksum->uuid;
            $fileEntry->uses_id   = $matchingFileChecksum->id;
        }

        $fileEntry->save();

        if ( ! empty($existing)) {
            // Existing files to mark as not current
            File::whereIn('id', $existing->pluck('id'))->update(['current' => false]);
        }

        return $fileEntry;
    }

}