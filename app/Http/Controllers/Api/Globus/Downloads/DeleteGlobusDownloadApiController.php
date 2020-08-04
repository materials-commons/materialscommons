<?php

namespace App\Http\Controllers\Api\Globus\Downloads;

use App\Actions\Globus\Downloads\DeleteGlobusDownloadAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;

class DeleteGlobusDownloadApiController extends Controller
{

    public function __invoke($projectId, GlobusUploadDownload $globus)
    {
        $deleteGlobusDownloadAction = new DeleteGlobusDownloadAction(GlobusApi::createGlobusApi());
        $deleteGlobusDownloadAction($globus);
    }
}
