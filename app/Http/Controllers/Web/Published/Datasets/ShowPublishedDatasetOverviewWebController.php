<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\Projects\UserProjects;
use App\ViewModels\Published\Datasets\ShowPublishedDatasetOverviewViewModel;
use Illuminate\Support\Facades\DB;

class ShowPublishedDatasetOverviewWebController extends Controller
{
    use ViewsAndDownloads;
    use GoogleDatasetAnnotations;
    use UserProjects;

    public function __invoke($datasetId)
    {
        $this->incrementDatasetViews($datasetId);
        $dataset = Dataset::with(['workflows', 'tags', 'rootDir'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        $readme = File::where('name', "readme.md")
                      ->where("dataset_id", $datasetId)
                      ->where("directory_id", $dataset->rootDir->id)
                      ->whereNull('deleted_at')
                      ->first();

        if (auth()->check()) {
            $userProjects = $this->getUserProjects(auth()->id());
        } else {
            $userProjects = collect();
        }

        $showPublishedDatasetOverviewViewModel = (new ShowPublishedDatasetOverviewViewModel())
            ->withDataset($dataset)
            ->withReadme($readme)
            ->withUserProjects($userProjects)
            ->withDsAnnotation($this->jsonLDAnnotations($dataset))
            ->withActivitiesGroup($this->getActivitiesGroup($datasetId))
            ->withFileTypes($this->getFileTypesGroup($dataset->id))
            ->withTotalFilesSize($this->getDatasetTotalFilesSize($dataset->id));
        return view('public.datasets.show', $showPublishedDatasetOverviewViewModel);
    }

    private function getActivitiesGroup($datasetId)
    {
        return DB::table('activities')
                 ->select('name', DB::raw('count(*) as count'))
                 ->whereIn('id',
                     DB::table('dataset2entity')
                       ->where('dataset2entity.dataset_id', $datasetId)
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
        $queryString = DB::raw($query)->getValue(DB::connection()->getQueryGrammar());
        $results = DB::select($queryString);
        return $results[0];
    }

    private function getFileTypesGroup($datasetId)
    {
        return DB::table('dataset2file')
                 ->where('dataset2file.dataset_id', $datasetId)
                 ->join('files', 'files.id', '=', 'dataset2file.file_id')
                 ->where('files.mime_type', '<>', 'directory')
                 ->select('files.mime_type', DB::raw('count(*) as count'))
                 ->groupBy('mime_type')
                 ->orderBy('mime_type')
                 ->get()
                 ->flatMap(function ($item) {
                     return [$item->mime_type => $item->count];
                 })
                 ->all();;
    }

    private function getDatasetTotalFilesSize($datasetId)
    {
        return DB::table('dataset2file')
                 ->where('dataset2file.dataset_id', $datasetId)
                 ->join('files', 'files.id', '=', 'dataset2file.file_id')
                 ->where('files.mime_type', '<>', 'directory')
                 ->distinct()
                 ->select('files.size', 'file.id')
                 ->sum('files.size');
    }
}