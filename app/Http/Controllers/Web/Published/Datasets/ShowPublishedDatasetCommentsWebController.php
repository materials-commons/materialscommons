<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;
use function auth;
use function view;

class ShowPublishedDatasetCommentsWebController extends Controller
{
    use NotificationChecker;

    public function __invoke($datasetId)
    {
        $dataset = Dataset::with(['comments.owner', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);
        $user = auth()->user();

        return view('public.datasets.show', [
            'dataset'                    => $dataset,
            'user'                       => $user,
            'hasNotificationsForDataset' => $this->datasetAlreadySetForNotificationForUser(auth()->user(), $dataset),
        ]);
    }
}
