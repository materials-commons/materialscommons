<?php

namespace App\Http\Controllers\Api\Globus\Uploads;

use App\Enums\GlobusType;
use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;

class IndexProjectGlobusUploadsApiController extends Controller
{
    public function __invoke($projectId)
    {
        return GlobusUploadDownload::with('owner')
                                   ->where('project_id', $projectId)
                                   ->where('type', GlobusType::ProjectUpload)
                                   ->get();
    }
}
