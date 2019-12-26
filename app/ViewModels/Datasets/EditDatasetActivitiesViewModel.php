<?php

namespace App\ViewModels\Datasets;

use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class EditDatasetActivitiesViewModel extends ViewModel
{
    private $dataset;
    private $project;
    private $user;

    public function __construct(Project $project, Dataset $dataset, $user)
    {
        $this->project = $project;
        $this->dataset = $dataset;
        $this->user = $user;
    }

    public function project()
    {
        return $this->project;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function user()
    {
        return $this->user;
    }

    public function activityInDataset(Activity $activity)
    {
        return $this->dataset->activities->contains($activity->id);
    }

    public function activityExperiments(Activity $activity)
    {
        $experimentNames = $activity->experiments->map(function (Experiment $e) {
            return $e->name;
        });
        return implode(",", $experimentNames->toArray());
    }
}
