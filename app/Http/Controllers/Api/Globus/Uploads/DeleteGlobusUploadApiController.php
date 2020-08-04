<?php

namespace App\Http\Controllers\Api\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteGlobusUploadApiController extends Controller
{
    public function __invoke($projectId, GlobusUploadDownload $globus)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globus->globus_endpoint_id, $globus->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globus->delete();
        Storage::disk('mcfs')->deleteDirectory("__globus_uploads/{$globus->uuid}");
    }
}
