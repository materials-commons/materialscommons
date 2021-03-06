<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Enums\GlobusStatus;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class MarkGlobusUploadAsCompleteWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusUpload)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globusUpload->globus_endpoint_id, $globusUpload->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globusUpload->update(['status' => GlobusStatus::Done]);

        return redirect(route('projects.globus.uploads.index', [$project]));
    }
}
