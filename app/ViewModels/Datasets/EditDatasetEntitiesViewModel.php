<?php

namespace App\ViewModels\Datasets;

use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class EditDatasetEntitiesViewModel extends ViewModel
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

    public function entityInDataset(Entity $entity)
    {
        return $this->dataset->entities->contains($entity->id);
    }

    public function entityExperiments(Entity $entity)
    {
        $experimentNames = $entity->experiments->map(function(Experiment $e) {
            return $e->name;
        })->toArray();
        return implode(",", $experimentNames);
    }
}
