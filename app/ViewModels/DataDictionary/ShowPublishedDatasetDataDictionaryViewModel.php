<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Dataset;
use Spatie\ViewModels\ViewModel;

class ShowPublishedDatasetDataDictionaryViewModel extends ViewModel
{
    /** @var \App\Models\Dataset */
    private $dataset;

    public function withDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function activityAttributeRoute($attrName)
    {
        return "#";
    }

    public function entityAttributeRoute($attrName)
    {
        return "#";
    }
}
