<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;

class ShowPublishedDatasetFilesWebController extends Controller
{
    use NotificationChecker;

    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('tags')
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        return view('public.datasets.show', [
            'dataset'                    => $dataset,
            'hasNotificationsForDataset' => $this->datasetAlreadySetForNotificationForUser(auth()->user(), $dataset),
        ]);
    }
}
