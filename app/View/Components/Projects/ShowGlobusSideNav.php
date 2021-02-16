<?php

namespace App\View\Components\Projects;

use App\Models\GlobusRequest;
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
            'globusRequest' => GlobusRequest::where('project_id', $this->project->id)
                                            ->where('owner_id', auth()->id())
                                            ->first(),
            'show'          => $this->userInBeta(),
        ]);
    }

    private function userInBeta(): bool
    {
        switch (auth()->id()) {
            case 343: /* Reza */
            case 316: /* Tracy Berman */
            case 173: /* John Allison */
            case 130: /* Glenn Tarcea */
            case 101: /* David Montiel */
            case 65:
                return true; /* Brian Puchala */
            default:
                return false;
        }
    }
}
