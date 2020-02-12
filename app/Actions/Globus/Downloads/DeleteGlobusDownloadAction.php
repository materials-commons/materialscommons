<?php

namespace App\Actions\Globus\Downloads;

use App\Actions\Globus\GlobusApi;
use App\Models\GlobusUploadDownload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteGlobusDownloadAction
{
    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function __invoke(GlobusUploadDownload $globusDownload)
    {
        try {
            $this->globusApi->deleteEndpointAclRule($globusDownload->globus_endpoint_id,
                $globusDownload->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        try {
            Storage::disk('mcfs')->deleteDirectory("__globus_downloads/{$globusDownload->uuid}");
        } catch (\Exception $e) {
            Log::error("Unable to delete download dir __globus_downloads/{$globusDownload->uuid}");
        }

        $globusDownload->delete();
    }
}
