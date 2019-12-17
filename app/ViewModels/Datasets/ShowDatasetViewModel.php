<?php

namespace App\ViewModels\Datasets;

use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowDatasetViewModel extends ViewModel
{
    private $dataset;
    private $project;
    private $communities;

    public function __construct(Project $project, Dataset $dataset, $communities)
    {
        $this->project = $project;
        $this->dataset = $dataset;
        $this->communities = $communities;
    }

    public function project()
    {
        return $this->project;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function communities()
    {
        return $this->communities;
    }

    public function datasetHasCommunity(Community $community)
    {
        return $this->dataset->communities->contains($community->id);
    }

    public function datasetCommunityIds()
    {
        return $this->dataset->communities->map(function ($item) {
            return $item->id;
        });
    }
}
