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
            ->withEntityAttributes($this->getUniqueEntityAttributesForExperiment($experiment->id))
            ->withActivityAttributes($this->getUniqueActivityAttributesForExperiment($experiment->id));
        return view('app.projects.experiments.show', $viewModel);
    }
}