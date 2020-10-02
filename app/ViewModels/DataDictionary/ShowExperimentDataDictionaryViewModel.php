<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Experiment;
use App\Models\Project;

class ShowExperimentDataDictionaryViewModel extends AbstractShowDataDictionaryViewModel
{
    use AttributeStatistics;

    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Experiment */
    private $experiment;

    private $excelFilesCount;

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function withExperiment(Experiment $experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    public function withExcelFilesCount($excelFilesCount)
    {
        $this->excelFilesCount = $excelFilesCount;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function experiment()
    {
        return $this->experiment;
    }

    public function activityAttributeRoute($attrName)
    {
        return route('projects.experiments.activity-attributes.show',
            [$this->project, $this->experiment, 'attribute' => $attrName]);
    }

    public function entityAttributeRoute($attrName)
    {
        return route('projects.experiments.entity-attributes.show',
            [$this->project, $this->experiment, 'attribute' => $attrName]);
    }

    public function excelFilesCount()
    {
        return $this->excelFilesCount;
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
