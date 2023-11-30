<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;

class ShowExperimentWorkflowWebController extends Controller
{
    use ExcelFilesCount;
    use DataDictionaryQueries;
    use EtlRunsCount;

    public function __invoke($projectId, $experimentId)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        $experiment = Experiment::withCount('experimental_entities', 'computational_entities', 'activities',
            'workflows')
                                ->with(['workflows', 'etlruns.files'])
                                ->findOrFail($experimentId);
        $excelFilesCount = $this->getExcelFilesCount($project);

        // Datatables does case-insensitive sorting. The database is returning case sensitive, so
        // create a case insensitive list of the workflow items
        $workflows = $experiment->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        return view('app.projects.experiments.show', [
            'project'                 => $project,
            'experiment'              => $experiment,
            'workflows'               => $workflows,
            'excelFilesCount'         => $excelFilesCount,
            'etlRunsCount'            => $this->getEtlRunsCount($experiment->etlruns),
            'activityAttributesCount' => $this->getUniqueActivityAttributesForExperiment($experiment->id)->count(),
            'entityAttributesCount'   => $this->getUniqueEntityAttributesForExperiment($experiment->id)->count(),
        ]);
    }
}
