<?php

namespace App\ViewModels\Entities;

use Spatie\ViewModels\ViewModel;

class ShowEntityViewModel extends ViewModel
{
    /**
     * @var \App\Models\Project
     */
    private $project;

    /**
     * @var \App\Models\Experiment
     */
    private $experiment;

    private $attributes;

    /**
     * @var \App\Models\Entity
     */
    private $entity;

    public function __construct($project, $entity)
    {
        $this->project = $project;
        $this->entity = $entity;
        $this->experiment = null;
        $this->attributes = collect();
    }

    public function setExperiment($experiment)
    {
        $this->experiment = $experiment;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function project()
    {
        return $this->project;
    }

    public function experiment()
    {
        return $this->experiment;
    }

    public function entity()
    {
        return $this->entity;
    }

    public function attributes()
    {
        return $this->attributes;
    }

    public function showFileRoute($file)
    {
        if (is_null($this->experiment)) {
            return route('projects.files.show', [$this->project, $file]);
        }

        // show experiment route
        return route('projects.files.show', [$this->project, $file]);
    }

    public function showActivityRoute($activity)
    {
        if (is_null($this->experiment)) {
            return route('projects.activities.show', [$this->project, $activity]);
        }

        // show experiment route
        return route('projects.activities.show', [$this->project, $activity]);
    }
}
