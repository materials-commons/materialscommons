<?php

namespace App\Actions\Globus;

use App\Models\GlobusRequest;
use App\Models\GlobusRequestFile;
use Illuminate\Support\LazyCollection;

class ImportGlobusFilesIntoProjectAction
{
    public function execute(GlobusRequest $globusRequest)
    {
        foreach ($this->getGlobusRequestFiles($globusRequest->id) as $globusRequestFile) {
            if ($globusRequestFile->file->isDir()) {
                continue;
            }

            $globusRequestFile->file->setAsActiveFile();
        }
    }

    private function getGlobusRequestFiles($globusRequestId): LazyCollection
    {
        return GlobusRequestFile::with('file.directory')
                                ->where('globus_request_id', $globusRequestId)
                                ->cursor();
    }
}