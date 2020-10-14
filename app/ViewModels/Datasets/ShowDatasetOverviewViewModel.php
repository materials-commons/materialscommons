<?php

namespace App\ViewModels\Datasets;

use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Concerns\HasOverviews;
use Spatie\ViewModels\ViewModel;

class ShowDatasetOverviewViewModel extends ViewModel
{
    use HasOverviews;

    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Dataset */
    private $dataset;

    public function __construct()
    {
        $this->entities = collect();
    }

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function withDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function dataset()
    {
        return $this->dataset;
    }
}
