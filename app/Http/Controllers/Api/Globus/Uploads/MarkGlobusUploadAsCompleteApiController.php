<?php

namespace App\Http\Controllers\Api\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Enums\GlobusStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\GlobusRequest;
use App\Http\Resources\Globus\GlobusUploadDownloadResource;
use App\Models\GlobusUploadDownload;
use Illuminate\Support\Facades\Log;

class MarkGlobusUploadAsCompleteApiController extends Controller
{
    public function __invoke(GlobusRequest $request, GlobusUploadDownload $globusUpload)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globusUpload->globus_endpoint_id, $globusUpload->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globusUpload->update(['status' => GlobusStatus::Done]);
        return new GlobusUploadDownloadResource($globusUpload);
    }
}
