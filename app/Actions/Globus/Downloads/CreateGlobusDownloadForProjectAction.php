<?php

namespace App\Actions\Globus\Downloads;

use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\GlobusUploadDownload;
use App\Models\User;

class CreateGlobusDownloadForProjectAction
{
    public function __invoke($data, $projectId, User $user)
    {
        $data['project_id'] = $projectId;
        $data['owner_id'] = $user->id;
        $data['type'] = GlobusType::ProjectDownload;
        $data['status'] = GlobusStatus::NotStarted;

        return GlobusUploadDownload::create($data);
    }
}