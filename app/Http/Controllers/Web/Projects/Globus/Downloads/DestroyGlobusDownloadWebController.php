<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Actions\Globus\Downloads\DeleteGlobusDownloadAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class DestroyGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusDownload)
    {
        $deleteGlobusDownloadAction = new DeleteGlobusDownloadAction(GlobusApi::createGlobusApi());
        $deleteGlobusDownloadAction($globusDownload);

        return redirect(route('projects.globus.downloads.index', [$project]));
    }
}
