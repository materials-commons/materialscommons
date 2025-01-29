<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Models\Dataset;
use App\Models\User;
use App\Traits\Notifications\NotificationChecker;
use App\Traits\Projects\UserProjects;
use function auth;
use function collect;

trait LoadDatasetContext
{
    use NotificationChecker;
    use UserProjects;

    private Dataset $dataset;
    private $userProjects;
    private ?User $user;
    private bool $hasNotificationsForDataset;

    private function loadDatasetContext($datasetId)
    {
        $this->dataset = Dataset::with(['comments.owner', 'tags', 'rootDir'])
                                ->withCount(['views', 'downloads'])
                                ->withCounts()
                                ->findOrFail($datasetId);
        $this->user = auth()->user();

        if (auth()->check()) {
            $this->userProjects = $this->getUserProjects(auth()->id());
        } else {
            $this->userProjects = collect();
        }

        $this->hasNotificationsForDataset = $this->datasetAlreadySetForNotificationForUser(auth()->user(),
            $this->dataset);
    }
}