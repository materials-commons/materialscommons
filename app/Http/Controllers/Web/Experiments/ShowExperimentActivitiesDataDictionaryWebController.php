<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowExperimentDataDictionaryViewModel;

class ShowExperimentActivitiesDataDictionaryWebController
{
    use ExcelFilesCount;
    use DataDictionaryQueries;

    public function __invoke(Project $project, Experiment $experiment)
    {
        $viewModel = (new ShowExperimentDataDictionaryViewModel())
            ->withProject($project)
            ->withExperiment($experiment)
            ->withExcelFilesCount($this->getExcelFilesCount($project))
            ->withActivityAttributes($this->getUniqueActivityAttributesForExperiment($experiment->id));
        return view('app.projects.experiments.show', $viewModel);
    }
}