<?php

namespace App\ViewModels\Experiments;

use App\Models\Workflow;
use Spatie\ViewModels\ViewModel;

class ExperimentWorkflowsViewModel extends ViewModel
{
    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Experiment */
    private $experiment;

    /** @var \App\Models\User */
    private $user;

    public function withProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function withExperiment($experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    public function experiment()
    {
        return $this->experiment;
    }

    public function withUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    public function workflowInExperiment(Workflow $workflow)
    {
        return $this->experiment->workflows->contains($workflow);
    }
}
