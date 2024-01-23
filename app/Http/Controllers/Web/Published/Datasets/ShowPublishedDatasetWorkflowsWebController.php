<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;
use App\Traits\Projects\UserProjects;
use function auth;
use function collect;

class ShowPublishedDatasetWorkflowsWebController extends Controller
{
    use ViewsAndDownloads;
    use GoogleDatasetAnnotations;
    use NotificationChecker;
    use UserProjects;

    public function __invoke($datasetId)
    {
        $this->incrementDatasetViews($datasetId);
        $dataset = Dataset::with(['workflows', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        if (auth()->check()) {
            $userProjects = $this->getUserProjects(auth()->id());
        } else {
            $userProjects = collect();
        }

        // Datatables does case-insensitive sorting. The database is returning case sensitive, so
        // create a case insensitive list of the workflow items
        $workflows = $dataset->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        return view('public.datasets.show', [
            'dataset'                    => $dataset,
            'userProjects' => $userProjects,
            'workflows'                  => $workflows,
            'hasNotificationsForDataset' => $this->datasetAlreadySetForNotificationForUser(auth()->user(), $dataset),
            'dsAnnotation'               => $this->jsonLDAnnotations($dataset),
        ]);
    }
}
