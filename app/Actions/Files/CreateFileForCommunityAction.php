<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Models\File;
use Ramsey\Uuid\Uuid;

class CreateFileForCommunityAction
{
    use SaveFile;

    public function execute($community, $description, $summary, $file)
    {
        $fileEntry = new File([
            'uuid'        => Uuid::uuid4()->toString(),
            'checksum'    => md5_file($file->getRealPath()),
            'mime_type'   => $file->getClientMimeType(),
            'size'        => $file->getSize(),
            'name'        => $file->getClientOriginalName(),
            'owner_id'    => auth()->id(),
            'current'     => true,
            'description' => $description,
            'summary'     => $summary,
        ]);

        $this->saveFile($file, $fileEntry->uuid);
        $fileEntry->save();
        $fileEntry->communities()->attach($community);

        if ($fileEntry->shouldBeConverted()) {
            ConvertFileJob::dispatch($fileEntry)->onQueue('globus');
        }

        return $fileEntry;
    }
}