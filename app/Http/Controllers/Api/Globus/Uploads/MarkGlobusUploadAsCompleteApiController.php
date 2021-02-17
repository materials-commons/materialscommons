<?php

namespace App\Http\Controllers\Api\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Enums\GlobusStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\GlobusFormRequest;
use App\Http\Resources\Globus\GlobusUploadDownloadResource;
use App\Models\GlobusUploadDownload;
use Illuminate\Support\Facades\Log;

class MarkGlobusUploadAsCompleteApiController extends Controller
{
    public function __invoke(GlobusFormRequest $request, GlobusUploadDownload $globus)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globus->globus_endpoint_id, $globus->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globus->update(['status' => GlobusStatus::Done]);
        return new GlobusUploadDownloadResource($globus);
    }
}
