<?php

namespace App\View\Components\Projects;

use App\Models\File;
use App\Models\Project;
use Illuminate\View\Component;

class ListNotebooks extends Component
{
    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $notebooks = File::with(['directory', 'owner'])
            ->where('project_id', $this->project->id)
            ->where(function ($query) {
                $query->where('name', 'like', '%.ipynb')
                    ->orWhere('name', 'like', '%.xlsx');
            })
            ->get();
        return view('components.projects.list-notebooks', ['notebooks' => $notebooks]);
    }
}
