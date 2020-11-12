<?php

namespace App\ViewModels\Experiments;

use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\Concerns\HasOverviews;
use Spatie\ViewModels\ViewModel;

class ShowExperimentViewModel extends ViewModel
{
    use HasOverviews;

    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Experiment */
    private $experiment;

    private $excelFilesCount;

    private $activityAttributesCount;

    private $entityAttributesCount;

    private $etlRunsCount;

    public function __construct()
    {
        $this->activityAttributesCount = 0;
        $this->entityAttributesCount = 0;
    }

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function withExperiment(Experiment $experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    public function experiment()
    {
        return $this->experiment;
    }

    public function withExcelFilesCount($excelFilesCount)
    {
        $this->excelFilesCount = $excelFilesCount;
        return $this;
    }

    public function excelFilesCount()
    {
        return $this->excelFilesCount;
    }

    public function withActivityAttributesCount($count)
    {
        $this->activityAttributesCount = $count;
        return $this;
    }

    public function activityAttributesCount()
    {
        return $this->activityAttributesCount;
    }

    public function withEntityAttributesCount($count)
    {
        $this->entityAttributesCount = $count;
        return $this;
    }

    public function entityAttributesCount()
    {
        return $this->entityAttributesCount;
    }

    public function withEtlRunsCount($count)
    {
        $this->etlRunsCount = $count;
        return $this;
    }

    public function etlRunsCount()
    {
        return $this->etlRunsCount;
    }
}
