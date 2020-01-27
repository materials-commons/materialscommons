<?php

namespace App\Actions\Globus\Downloads;

use App\Models\GlobusUploadDownload;
use App\Models\User;

class CreateGlobusDownloadForProjectAction
{
    public function __invoke($data, $projectId, User $user)
    {
        $data['project_id'] = $projectId;
        $data['owner_id'] = $user->id;
        $data['loading'] = false;
        $data['uploading'] = false;
        $data['type'] = 'download';

        return GlobusUploadDownload::create($data);
    }
}