<?php

namespace App\ViewModels\Datasets;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowDatasetViewModel extends ViewModel
{
    private $dataset;
    private $project;
    private $experiments;

    public function __construct(Project $project, Dataset $dataset, $experiments)
    {
        $this->project = $project;
        $this->dataset = $dataset;
        $this->experiments = $experiments;
    }

    public function project()
    {
        return $this->project;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function experiments()
    {
        return $this->experiments;
    }

    public function datasetHasExperiment(Experiment $experiment)
    {
        return $this->dataset->experiments->contains($experiment->id);
    }

    public function datasetExperimentIds()
    {
        return $this->dataset->experiments->map(function ($item) {
            return $item->id;
        });
    }
}
