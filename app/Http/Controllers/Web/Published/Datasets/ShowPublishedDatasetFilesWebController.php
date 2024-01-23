<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;
use App\Traits\Projects\UserProjects;
use function auth;
use function collect;

class ShowPublishedDatasetFilesWebController extends Controller
{
    use NotificationChecker;
    use UserProjects;

    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('tags')
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        if (auth()->check()) {
            $userProjects = $this->getUserProjects(auth()->id());
        } else {
            $userProjects = collect();
        }

        return view('public.datasets.show', [
            'userProjects' => $userProjects,
            'dataset'                    => $dataset,
            'hasNotificationsForDataset' => $this->datasetAlreadySetForNotificationForUser(auth()->user(), $dataset),
        ]);
    }
}
