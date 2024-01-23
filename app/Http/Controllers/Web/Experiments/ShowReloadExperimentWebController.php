<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Etl\GetFileByPathAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Sheet;
use App\Traits\Experiments\SharedDatasets;

class ShowReloadExperimentWebController extends Controller
{
    use SharedDatasets;

    public function __invoke(Project $project, Experiment $experiment)
    {
        $datasetIds = $this->getDatasetsListSharingEntitiesWithExperiment($experiment);
        $sheets = Sheet::where('project_id', $project->id)->get();
        $file = null;
        if (!is_null($experiment->loaded_file_path)) {
            $getFileByPathAction = new GetFileByPathAction();
            $file = $getFileByPathAction->execute($project->id, $experiment->loaded_file_path);
        }

        $sheet = null;
        if (!is_null($experiment->sheet_id)) {
            $sheet = Sheet::where('id', $experiment->sheet_id)
                          ->where('project_id', $project->id)
                          ->first();
        }

        return view('app.projects.experiments.reload', [
            'project'             => $project,
            'sheets' => $sheets,
            'file'   => $file,
            'sheet'  => $sheet,
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
