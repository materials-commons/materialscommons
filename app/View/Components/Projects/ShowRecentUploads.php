<?php

namespace App\View\Components\Projects;

use App\Models\Project;
use Illuminate\View\Component;

class ShowRecentUploads extends Component
{
    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('components.projects.show-recent-uploads');
    }
}