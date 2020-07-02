<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowProjectDataDictionaryViewModel extends ViewModel
{
    use AttributeStatistics;

    /** @var \App\Models\Project */
    private $project;
    private $entityAttributes;
    private $activityAttributes;

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function withEntityAttributes($entityAttributes)
    {
        $this->entityAttributes = $entityAttributes;
        return $this;
    }

    public function withActivityAttributes($activityAttributes)
    {
        $this->activityAttributes = $activityAttributes;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function entityAttributes()
    {
        return $this->entityAttributes;
    }

    public function activityAttributes()
    {
        return $this->activityAttributes;
    }
}
