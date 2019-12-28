<?php

namespace App\ViewModels\Datasets;

use App\Models\Activity;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Workflow;
use Spatie\ViewModels\ViewModel;

class EditDatasetViewModel extends ViewModel
{
    private $dataset;
    private $project;
    private $user;
    private $communities;
    private $experiments;
    private $workflows;

    public function __construct(Project $project, Dataset $dataset, $user)
    {
        $this->project = $project;
        $this->dataset = $dataset;
        $this->user = $user;
        $this->communities = collect();
        $this->experiments = collect();
        $this->workflows = collect();
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

    public function withCommunities($communities)
    {
        $this->communities = $communities;
        return $this;
    }

    public function communities()
    {
        return $this->communities;
    }

    public function withExperiments($experiments)
    {
        $this->experiments = $experiments;
        return $this;
    }

    public function experiments()
    {
        return $this->experiments;
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

    public function workflowInDataset(Workflow $workflow)
    {
        return $this->dataset->workflows->contains($workflow->id);
    }

    public function workflowExperiments(Workflow $workflow)
    {
        $experimentNames = $workflow->experiments->map(function (Experiment $e) {
            return $e->name;
        })->toArray();
        return implode(",", $experimentNames);
    }

    public function entityInDataset(Entity $entity)
    {
        return $this->dataset->entities->contains($entity->id);
    }

    public function entityExperiments(Entity $entity)
    {
        $experimentNames = $entity->experiments->map(function (Experiment $e) {
            return $e->name;
        })->toArray();
        return implode(",", $experimentNames);
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
