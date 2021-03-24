<?php

namespace App\View\Components\Datasets;

use App\Models\Project;
use Illuminate\View\Component;

class CreateAuthorsTable extends Component
{
    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('components.datasets.create-authors-table');
    }
}
