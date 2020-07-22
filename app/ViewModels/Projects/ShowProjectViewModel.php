<?php

namespace App\ViewModels\Projects;

use App\Models\Project;
use App\ViewModels\Concerns\HasOverviews;
use Spatie\ViewModels\ViewModel;

class ShowProjectViewModel extends ViewModel
{
    use HasOverviews;

    /** @var \App\Models\Project */
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function project()
    {
        return $this->project;
    }
}
