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

    public function __invoke($projectId, $experimentId)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        $experiment = Experiment::withCount('entities', 'activities', 'workflows', 'etlRuns')
                                ->with('etlRuns.files')
                                ->findOrFail($experimentId);
        $excelFilesCount = $this->getExcelFilesCount($project);
        return view('', [
            'project'                 => $project,
            'experiment'              => $experiment,
            'excelFilesCount'         => $excelFilesCount,
            'activityAttributesCount' => $this->getUniqueActivityAttributesForExperiment($experiment->id)->count(),
            'entityAttributesCount'   => $this->getUniqueEntityAttributesForExperiment($experiment->id)->count(),
        ]);
    }
}
