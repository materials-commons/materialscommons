<?php

namespace App\Actions\Globus\Downloads;

use App\Models\GlobusUploadDownload;

class CreateGlobusProjectDownloadsDirsAction
{
    private $globusApi;

    public function __construct(GlobusUploadDownload $globusDownload)
    {
        $this->globusDownload = $globusDownload;
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.q
    }
}