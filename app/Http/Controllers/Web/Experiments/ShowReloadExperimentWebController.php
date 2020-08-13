<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ShowReloadExperimentWebController extends Controller
{
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
                       ->where('current', true)
                       ->get();
    }

    private function getDatasetsListSharingEntitiesWithExperiment(Experiment $experiment)
    {
        $experiment->load('entities');
        return DB::table('dataset2entity')
                 ->select('dataset_id')
                 ->whereIn('entity_id',
                     $experiment->entities->pluck('id')->toArray())
                 ->distinct()
                 ->get()
                 ->pluck('dataset_id');
    }

    private function getAffectedPublishedDatasets(Collection $datasetIds)
    {
        if ($datasetIds->isEmpty()) {
            return collect([]);
        }

        return Dataset::whereIn('id', $datasetIds->toArray())
                      ->whereNotNull('published_at')
                      ->get();
    }

    private function getAffectedUnpublishedDatasets(Collection $datasetIds)
    {
        if ($datasetIds->isEmpty()) {
            return collect([]);
        }

        return Dataset::whereIn('id', $datasetIds->toArray())
                      ->whereNull('published_at')
                      ->get();
    }
}
