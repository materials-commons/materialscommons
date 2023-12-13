<?php

namespace App\View\Components\Projects;

use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\Component;

class ShowOldGlobusSideNav extends Component
{
    public Project $project;
    public User $user;

    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    public function render()
    {
        $globusUpload = GlobusUploadDownload::where('project_id', $this->project->id)
                                            ->where('owner_id', $this->user->id)
                                            ->where('status', GlobusStatus::Loading)
                                            ->where('type', GlobusType::ProjectUpload)
                                            ->first();
        $globusDownload = GlobusUploadDownload::where('project_id', $this->project->id)
                                              ->where('status', GlobusStatus::Loading)
                                              ->where('type', GlobusType::ProjectDownload)
                                              ->where('owner_id', $this->user->id)
                                              ->whereNotNull('globus_acl_id')
                                              ->first();
        return view('components.projects.show-old-globus-side-nav', [
            'globusUpload'   => $globusUpload,
            'globusDownload' => $globusDownload,
        ]);
    }
}
