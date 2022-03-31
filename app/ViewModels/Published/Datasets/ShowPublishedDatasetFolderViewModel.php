<?php

namespace App\ViewModels\Published\Datasets;

use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;
use App\ViewModels\Folders\ShowFolderViewModel;
use function auth;

class ShowPublishedDatasetFolderViewModel extends ShowFolderViewModel
{
    use NotificationChecker;

    public function hasNotificationsForDataset(): bool
    {
        return $this->userAlreadySetForNotification(auth()->id(), Dataset::class, $this->dataset->id);
    }
}