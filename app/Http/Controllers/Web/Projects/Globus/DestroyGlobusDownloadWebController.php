<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusUpload;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class DestroyGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUpload $globusUpload)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globusUpload->globus_endpoint_id, $globusUpload->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globusUpload->delete();

        return redirect(route('projects.globus.status', [$project]));
    }
}
