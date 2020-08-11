<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\ViewModels\Published\Datasets\ShowPublishedDatasetOverviewViewModel;
use Illuminate\Support\Facades\DB;

class ShowPublishedDatasetOverviewWebController extends Controller
{
    use ViewsAndDownloads;
    use GoogleDatasetAnnotations;

    public function __invoke($datasetId)
    {
        $this->incrementViews($datasetId);
        $dataset = Dataset::with(['workflows', 'tags'])->withCount(['views', 'downloads'])->findOrFail($datasetId);

        $showPublishedDatasetOverviewViewModel = (new ShowPublishedDatasetOverviewViewModel())
            ->withDataset($dataset)
            ->withDsAnnotation($this->jsonLDAnnotations($dataset))
            ->withActivitiesGroup($this->getActivitiesGroup($datasetId))
            ->withObjectCounts($this->getObjectTypes($datasetId));
        return view('public.datasets.show', $showPublishedDatasetOverviewViewModel);
    }

    private function getActivitiesGroup($datasetId)
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

    private function getObjectTypes($datasetId)
    {
        $query = "select (select count(*) from entities where id in (select entity_id from dataset2entity where dataset_id = {$datasetId})) as entitiesCount,".
            "(select count(*) from activities where id in (select activity_id from dataset2activity where dataset_id = {$datasetId})) as activitiesCount";
        $results = DB::select(DB::raw($query));
        return $results[0];
    }
}