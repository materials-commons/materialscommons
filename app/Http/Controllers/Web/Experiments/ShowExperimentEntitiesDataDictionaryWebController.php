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
    use EtlRunsCount;

    public function __invoke(Project $project, $experimentId)
    {
        $experiment = Experiment::withCount('entities', 'activities', 'workflows')
                                ->with('etlruns.files')
                                ->findOrFail($experimentId);
        $viewModel = (new ShowExperimentDataDictionaryViewModel())
            ->withProject($project)
            ->withExperiment($experiment)
            ->withEtlRunsCount($this->getEtlRunsCount($experiment->etlruns))
            ->withExcelFilesCount($this->getExcelFilesCount($project))
            ->withActivityAttributes($this->getUniqueActivityAttributesForExperiment($experiment->id))
            ->withEntityAttributes($this->getUniqueEntityAttributesForExperiment($experiment->id));
        return view('app.projects.experiments.show', $viewModel);
    }
}
