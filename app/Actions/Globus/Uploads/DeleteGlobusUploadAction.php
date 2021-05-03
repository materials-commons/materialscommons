<?php

namespace App\Actions\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Models\GlobusUploadDownload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteGlobusUploadAction
{
    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function __invoke(GlobusUploadDownload $globusUpload)
    {
        try {
            $this->globusApi->deleteEndpointAclRule($globusUpload->globus_endpoint_id,
                $globusUpload->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        try {
            Storage::disk('mcfs')->deleteDirectory("__globus_uploads/{$globusUpload->uuid}");
        } catch (\Exception $e) {
            Log::error("Unable to delete download dir __globus_uploads/{$globusUpload->uuid}");
        }

        $globusUpload->delete();
    }
}