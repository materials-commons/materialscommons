<?php

namespace App\ViewModels\Published\Datasets;

use App\Models\Dataset;
use App\Traits\Notifications\NotificationChecker;
use App\ViewModels\Concerns\HasOverviews;
use App\ViewModels\Files\FileView;
use Spatie\ViewModels\ViewModel;

class ShowPublishedDatasetOverviewViewModel extends ViewModel
{
    use HasOverviews;
    use NotificationChecker;

    /** @var \App\Models\Dataset */
    private $dataset;
    private $readme;

    private $dsAnnotation;

    public function withDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function withDsAnnotation($dsAnnotation)
    {
        $this->dsAnnotation = $dsAnnotation;
        return $this;
    }

    public function withReadme($file)
    {
        $this->readme = $file;
        return $this;
    }

    public function dsAnnotation()
    {
        return $this->dsAnnotation;
    }

    public function readme()
    {
        return $this->readme;
    }

    public function hasNotificationsForDataset(): bool
    {
        return $this->userAlreadySetForNotification(auth()->id(), Dataset::class, $this->dataset->id);
    }
}
