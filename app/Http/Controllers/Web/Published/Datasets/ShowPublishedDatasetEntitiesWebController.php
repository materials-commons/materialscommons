<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;
use App\Traits\Projects\UserProjects;
use Illuminate\Support\Facades\DB;
use function auth;
use function collect;

class ShowPublishedDatasetEntitiesWebController extends Controller
{
    use NotificationChecker;
    use UserProjects;

    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, $datasetId)
    {
        $dataset = Dataset::with(['entities.activities', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);
        $activities = DB::table('dataset2entity')
                        ->where('dataset2entity.dataset_id', $datasetId)
                        ->join('activity2entity', 'dataset2entity.entity_id', '=', 'activity2entity.entity_id')
                        ->join('activities', 'activity2entity.activity_id', '=', 'activities.id')
                        ->where('name', '<>', 'Create Samples')
                        ->select('name')
                        ->distinct()
                        ->orderBy('name')
                        ->get();

        if (auth()->check()) {
            $userProjects = $this->getUserProjects(auth()->id());
        } else {
            $userProjects = collect();
        }

        return view('public.datasets.show', [
            'dataset'                    => $dataset,
            'entities'                   => $dataset->entities,
            'userProjects' => $userProjects,
            'activities'                 => $activities,
            'hasNotificationsForDataset' => $this->datasetAlreadySetForNotificationForUser(auth()->user(), $dataset),
            'usedActivities'             => $createUsedActivities->execute($activities, $dataset->entities),
        ]);
    }
}
