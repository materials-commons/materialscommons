<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Project;

class ShowProjectDataDictionaryViewModel extends AbstractShowDataDictionaryViewModel
{
    use AttributeStatistics;

    /** @var \App\Models\Project */
    private $project;

    private $experiments;

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function project()
    {
        return $this->project;
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

    public function query()
    {
        return '';
    }

    public function activityAttributeRoute($attrName)
    {
        return route('projects.activity-attributes.show', [$this->project, 'attribute' => $attrName]);
    }

    public function entityAttributeRoute($attrName)
    {
        return route('projects.entity-attributes.show', [$this->project, 'attribute' => $attrName]);
    }

    public function entityAttributesCount()
    {
        return $this->entityAttributes->count();
    }

    public function activityAttributesCount()
    {
        return $this->activityAttributes->count();
    }
}
