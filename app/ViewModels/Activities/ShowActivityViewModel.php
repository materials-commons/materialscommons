<?php

namespace App\ViewModels\Activities;

use Spatie\ViewModels\ViewModel;

class ShowActivityViewModel extends ViewModel
{
    private $project;
    private $experiment;
    private $activity;

    public function __construct($project, $activity)
    {
        $this->project = $project;
        $this->activity = $activity;
        $this->experiment = null;
    }

    public function setExperiment($experiment)
    {
        $this->experiment = $experiment;
    }

    public function project()
    {
        return $this->project;
    }

    public function experiment()
    {
        return $this->experiment;
    }

    public function activity()
    {
        return $this->activity;
    }

    public function showFileRoute($file)
    {
        return is_null($this->experiment) ? route('projects.files.show', [$this->project, $file])
            : route('projects.files.show', [$this->project, $file]);
    }

    public function showEntityRoute($entity)
    {
        return is_null($this->experiment) ? route('projects.entities.show', [$this->project, $entity])
            : route('projects.entities.show', [$this->project, $entity]);
    }
}
