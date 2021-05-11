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
                                              ->first(),
            'show'           => $this->userInBeta(),
        ]);
    }

    private function userInBeta(): bool
    {
        switch (auth()->id()) {
            // case 343: /* Reza */
            // case 316: /* Tracy Berman */
            // case 173: /* John Allison */
            // case 101: /* David Montiel */
            case 130: /* Glenn Tarcea */
            case 65:  /* Brian Puchala */
                return true;
            default:
                return true;
        }
    }
}
