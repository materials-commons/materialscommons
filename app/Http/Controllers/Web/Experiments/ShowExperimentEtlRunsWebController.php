<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;

class ShowExperimentEtlRunsWebController extends Controller
{
    use ExcelFilesCount;
    use DataDictionaryQueries;
    use EtlRunsCount;

    public function __invoke($projectId, $experimentId)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        $experiment = Experiment::withCount('entities', 'activities', 'workflows')
                                ->with('etlruns.files')
                                ->findOrFail($experimentId);
        $excelFilesCount = $this->getExcelFilesCount($project);
        return view('app.projects.experiments.show', [
            'project'                 => $project,
            'experiment'              => $experiment,
            'excelFilesCount'         => $excelFilesCount,
            'etlRunsCount'            => $this->getEtlRunsCount($experiment->etlruns),
            'activityAttributesCount' => $this->getUniqueActivityAttributesForExperiment($experiment->id)->count(),
            'entityAttributesCount'   => $this->getUniqueEntityAttributesForExperiment($experiment->id)->count(),
        ]);
    }
}
