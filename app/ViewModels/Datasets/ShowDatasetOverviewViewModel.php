<?php

namespace App\ViewModels\Datasets;

use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Concerns\HasOverviews;
use App\ViewModels\Datasets\Traits\HasErrorsAndWarnings;
use Spatie\ViewModels\ViewModel;

class ShowDatasetOverviewViewModel extends ViewModel
{
    use HasOverviews;
    use HasErrorsAndWarnings;

    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Dataset */
    private $dataset;

    private $workflows;

    private $activities;

    private $usedActivities;

    private $files;

    private $directory;

    private $editRoute = '';

    private $showExperiment = false;

    private $category;

    public function __construct()
    {
        $this->entities = collect();
        $this->workflows = collect();
        $this->category = "experimental";
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

    public function withWorkflows($workflows)
    {
        $this->workflows = $workflows;
        return $this;
    }

    public function workflows()
    {
        return $this->workflows;
    }

    public function withActivities($activities)
    {
        $this->activities = $activities;
        return $this;
    }

    public function activities()
    {
        return $this->activities;
    }

    public function withUsedActivities($usedActivities)
    {
        $this->usedActivities = $usedActivities;
        return $this;
    }

    public function usedActivities()
    {
        return $this->usedActivities;
    }

    public function withFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    public function files()
    {
        return $this->files;
    }

    public function withDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    public function directory()
    {
        return $this->directory;
    }

    public function withCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function category()
    {
        return $this->category;
    }

    public function withEditRoute($route)
    {
        $this->editRoute = $route;
        return $this;
    }

    public function editRoute()
    {
        return $this->editRoute;
    }

    public function showExperiment()
    {
        return $this->showExperiment;
    }
}
