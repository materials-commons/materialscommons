<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetOverviewViewModel;
use Illuminate\Support\Facades\DB;

class ShowDatasetOverviewWebController extends Controller
{
    public function __invoke(Project $project, $datasetId)
    {
        $dataset = Dataset::with('experiments', 'tags')->findOrFail($datasetId);

        $showDatasetOverviewViewModel = (new ShowDatasetOverviewViewModel())
            ->withProject($project)
            ->withDataset($dataset)
            ->withActivitiesGroup($this->getActivitiesGroup($dataset->id))
            ->withObjectCounts($this->getObjectTypes($dataset->id));
        return view('app.projects.datasets.show', $showDatasetOverviewViewModel);
    }

    private function getActivitiesGroup(int $datasetId)
    {
        return DB::table('activities')
                 ->select('name', DB::raw('count(*) as count'))
                 ->whereIn('id',
                     DB::table('dataset2entity')
                       ->where('dataset_id', $datasetId)
                       ->join('activity2entity', 'dataset2entity.entity_id', '=', 'activity2entity.entity_id')
                       ->join('activities', 'activity2entity.activity_id', '=', 'activities.id')
                       ->select('activities.id')
                 )
                 ->groupBy('name')
                 ->orderBy('name')
                 ->get();
    }

    private function getObjectTypes(int $datasetId)
    {
        $query = "select (select count(*) from entities where id in (select entity_id from dataset2entity where dataset_id = {$datasetId})) as entitiesCount,".
            "(select count(*) from activities where id in (select activity_id from dataset2activity where dataset_id = {$datasetId})) as activitiesCount";
        $results = DB::select(DB::raw($query));
        return $results[0];
    }
}
