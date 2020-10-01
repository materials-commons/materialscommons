<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowExperimentDataDictionaryViewModel;

class ShowExperimentEntitiesDataDictionaryWebController extends Controller
{
    use ExcelFilesCount;
    use DataDictionaryQueries;

    public function __invoke(Project $project, Experiment $experiment)
    {
        $viewModel = (new ShowExperimentDataDictionaryViewModel())
            ->withProject($project)
            ->withExperiment($experiment)
            ->withExcelFilesCount($this->getExcelFilesCount($project))
            ->withEntityAttributes($this->getUniqueEntityAttributesForExperiment($experiment->id));
        return view('app.projects.experiments.show', $viewModel);
    }
}
