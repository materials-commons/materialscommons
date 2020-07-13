<?php

namespace App\Http\Controllers\Api\Globus\Downloads;

use App\Enums\GlobusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Globus\GlobusUploadDownloadResource;
use App\Models\GlobusUploadDownload;

class IndexProjectGlobusDownloadsApiController extends Controller
{
    public function __invoke($projectId)
    {
        $downloads = GlobusUploadDownload::with('owner')
                                         ->where('owner_id', auth()->id())
                                         ->where('project_id', $projectId)
                                         ->where('type', GlobusType::ProjectDownload)
                                         ->get();
        return GlobusUploadDownloadResource::collection($downloads);
    }
}
