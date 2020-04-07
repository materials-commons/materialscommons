<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestroyGlobusUploadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusUpload)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globusUpload->globus_endpoint_id, $globusUpload->globus_acl_id);
            Storage::disk('mcfs')->deleteDirectory("__globus_uploads/{$globusUpload->uuid}");
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globusUpload->delete();

        return redirect(route('projects.globus.uploads.index', [$project]));
    }
}
