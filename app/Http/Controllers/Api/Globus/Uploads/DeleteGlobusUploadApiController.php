<?php

namespace App\Http\Controllers\Api\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteGlobusUploadApiController extends Controller
{
    public function __invoke($projectId, GlobusUploadDownload $globusUpload)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globusUpload->globus_endpoint_id, $globusUpload->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globusUpload->delete();
        Storage::disk('mcfs')->deleteDirectory("__globus_uploads/{$globusUpload->uuid}");
    }
}
