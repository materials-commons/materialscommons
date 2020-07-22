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
}
