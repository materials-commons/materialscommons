<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Experiment;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowExperimentDataDictionaryViewModel extends ViewModel
{
    use AttributeStatistics;

    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Experiment */
    private $experiment;

    private $entityAttributes;
    private $activityAttributes;
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

    public function withExcelFilesCount($excelFilesCount)
    {
        $this->excelFilesCount = $excelFilesCount;
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

    public function experiment()
    {
        return $this->experiment;
    }

    public function excelFilesCount()
    {
        return $this->excelFilesCount;
    }
}
