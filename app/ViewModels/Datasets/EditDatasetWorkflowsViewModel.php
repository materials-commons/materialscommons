<?php

namespace App\ViewModels\Datasets;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Workflow;
use Spatie\ViewModels\ViewModel;

class EditDatasetWorkflowsViewModel extends ViewModel
{
    private $dataset;
    private $project;
    private $user;
    private $workflows;

    public function __construct(Project $project, Dataset $dataset, $user, $workflows)
    {
        $this->project = $project;
        $this->dataset = $dataset;
        $this->user = $user;
        $this->workflows = $workflows;
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

    public function workflows()
    {
        return $this->workflows;
    }

    public function workflowInDataset(Workflow $workflow)
    {
        return $this->dataset->workflows->contains($workflow->id);
    }

    public function workflowExperiments(Workflow $workflow)
    {
        $experimentNames = $workflow->experiments->map(function(Experiment $e) {
            return $e->name;
        })->toArray();
        return implode(",", $experimentNames);
    }
}