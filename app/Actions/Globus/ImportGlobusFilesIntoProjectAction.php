<?php

namespace App\Actions\Globus;

use App\Models\GlobusRequest;
use App\Models\GlobusRequestFile;

class ImportGlobusFilesIntoProjectAction
{
    public function execute(GlobusRequest $globusRequest)
    {
        foreach (
            GlobusRequestFile::with('file.directory')
                             ->where('globus_request_id', $globusRequest->id) as $globusRequestFile
        ) {
            if ($globusRequestFile->file->isDir()) {
                continue;
            }

            $globusRequestFile->file->setAsActiveFile();
        }
    }
}