<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Experiments\SharedDatasets;

class ShowReloadExperimentWebController extends Controller
{
    use SharedDatasets;

    public function __invoke(Project $project, Experiment $experiment)
    {
        $datasetIds = $this->getDatasetsListSharingEntitiesWithExperiment($experiment);

        return view('app.projects.experiments.reload', [
            'project'             => $project,
            'experiment'          => $experiment,
            'excelFiles'          => $this->getProjectExcelFiles($project),
            'publishedDatasets'   => $this->getAffectedPublishedDatasets($datasetIds),
            'unpublishedDatasets' => $this->getAffectedUnpublishedDatasets($datasetIds),
        ]);
    }

    private function getProjectExcelFiles(Project $project)
    {
        return $project->files()
                       ->with('directory')
                       ->where('mime_type', "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                       ->whereNull('dataset_id')
                       ->whereNull('deleted_at')
                       ->where('current', true)
                       ->get();
    }
}
