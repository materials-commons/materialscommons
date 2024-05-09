<?php

namespace App\View\Components\Projects;

use App\Models\GlobusTransfer;
use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function auth;

class ShowGlobus2SideNav extends Component
{
    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.projects.show-globus2-side-nav', [
            'globusTransfer' => GlobusTransfer::where('project_id', $this->project->id)
                                              ->where('owner_id', auth()->id())
                                              ->where('state', 'open')
                                              ->first(),
        ]);
    }
}
