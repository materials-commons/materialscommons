<?php

namespace App\View\Components\Projects;

use App\Models\GlobusTransfer;
use App\Models\Project;
use Illuminate\View\Component;

class ShowGlobusSideNav extends Component
{
    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('components.projects.show-globus-side-nav', [
            'globusTransfer' => GlobusTransfer::where('project_id', $this->project->id)
                                              ->where('owner_id', auth()->id())
                                              ->where('state', 'open')
                                              ->first(),
        ]);
    }
}
