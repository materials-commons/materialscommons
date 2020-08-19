<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Experiments\SharedDatasets;

class DeleteExperimentWebController extends Controller
{
    use SharedDatasets;

    public function __invoke(Project $project, Experiment $experiment)
    {
        $datasetIds = $this->getDatasetsListSharingEntitiesWithExperiment($experiment);

        return view('app.projects.experiments.delete', [
            'project'             => $project,
            'experiment'          => $experiment,
            'publishedDatasets'   => $this->getAffectedPublishedDatasets($datasetIds),
            'unpublishedDatasets' => $this->getAffectedUnpublishedDatasets($datasetIds),
        ]);
    }
}
