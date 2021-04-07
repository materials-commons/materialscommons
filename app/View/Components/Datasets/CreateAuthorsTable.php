<?php

namespace App\View\Components\Datasets;

use App\Models\Dataset;
use App\Models\Project;
use Illuminate\View\Component;

class CreateAuthorsTable extends Component
{
    public Project $project;
    public ?Dataset $dataset;

    public function __construct(Project $project, Dataset $dataset = null)
    {
        $this->project = $project;
        $this->dataset = $dataset;
    }

    public function render()
    {
        return view('components.datasets.create-authors-table');
    }
}
