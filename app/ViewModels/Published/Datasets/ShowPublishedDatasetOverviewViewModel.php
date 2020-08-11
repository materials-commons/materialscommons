<?php

namespace App\ViewModels\Published\Datasets;

use App\Models\Dataset;
use App\ViewModels\Concerns\HasOverviews;
use Spatie\ViewModels\ViewModel;

class ShowPublishedDatasetOverviewViewModel extends ViewModel
{
    use HasOverviews;

    /** @var \App\Models\Dataset */
    private $dataset;

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

    public function dsAnnotation()
    {
        return $this->dsAnnotation;
    }
}
