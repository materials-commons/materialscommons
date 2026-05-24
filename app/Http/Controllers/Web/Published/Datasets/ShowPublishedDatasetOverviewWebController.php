<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\User;
use App\Traits\Datasets\DatasetInfo;
use App\ViewModels\Published\Datasets\ShowPublishedDatasetOverviewViewModel;
use Illuminate\Support\Facades\DB;
use function blank;
use function trim;

class ShowPublishedDatasetOverviewWebController extends Controller
{
    use ViewsAndDownloads;
    use GoogleDatasetAnnotations;
    use LoadDatasetContext;
    use DatasetInfo;

    public function __invoke($datasetId)
    {
        $this->loadDatasetContext($datasetId);
        $this->incrementDatasetViews($datasetId);
        $readme = null;

        // Handle the case where the user published a dataset with directories, but no files
        if (!is_null($this->dataset->rootDir)) {
            $this->incrementDatasetDownloads($this->dataset->rootDir->id);
            $readme = File::where('name', "readme.md")
                          ->where("dataset_id", $datasetId)
                          ->where("directory_id", $this->dataset->rootDir->id)
                          ->whereNull('deleted_at')
                          ->first();
        }

        $showPublishedDatasetOverviewViewModel = (new ShowPublishedDatasetOverviewViewModel())
            ->withDataset($this->dataset)
            ->withReadme($readme)
            ->withUserProjects($this->userProjects)
            ->withDsAnnotation($this->jsonLDAnnotations($this->dataset))
            ->withActivitiesGroup($this->getActivitiesGroup($datasetId))
            ->withFileTypes($this->getFileTypesGroup($this->dataset->id))
            ->withTotalFilesSize($this->getDatasetTotalFilesSize($this->dataset->id))
            ->withAuthorUsers($this->resolveAuthorUsers($this->dataset->ds_authors ?? []));
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

    private function resolveAuthorUsers(array $dsAuthors): \Illuminate\Support\Collection
    {
        if (empty($dsAuthors)) {
            return collect();
        }

        $emailByName = [];
        foreach ($dsAuthors as $author) {
            $name = trim($author['name'] ?? '');
            if (blank($name) || isset($emailByName[$name])) {
                continue;
            }
            $emailByName[$name] = blank($author['email'] ?? '') ? null : trim($author['email']);
        }

        if (empty($emailByName)) {
            return collect();
        }

        $names = array_keys($emailByName);
        $emails = array_values(array_filter($emailByName));

        $users = User::where(function ($q) use ($names, $emails) {
            $q->whereIn('name', $names);
            if (!empty($emails)) {
                $q->orWhereIn('email', $emails);
            }
        })->get(['id', 'name', 'slug', 'email']);

        $byName = $users->keyBy('name');
        $byEmail = $users->keyBy('email');

        $lookup = collect();
        foreach ($emailByName as $authorName => $email) {
            if ($byName->has($authorName)) {
                $lookup->put($authorName, $byName->get($authorName));
            } elseif ($email && $byEmail->has($email)) {
                $lookup->put($authorName, $byEmail->get($email));
            }
        }

        return $lookup;
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
                 ->all();
    }
}
