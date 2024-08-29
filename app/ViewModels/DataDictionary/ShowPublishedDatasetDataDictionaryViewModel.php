<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Dataset;
use Spatie\ViewModels\ViewModel;
use function route;

class ShowPublishedDatasetDataDictionaryViewModel extends AbstractShowDataDictionaryViewModel
{
    use AttributeStatistics;

    /** @var \App\Models\Dataset */
    private $dataset;
    private $userProjects;
    private $hasNotificationsForDataset;

    public function withDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function dataset()
    {
        return $this->dataset;
    }


    public function withUserProjects($userProjects)
    {
        $this->userProjects = $userProjects;
        return $this;
    }

    public function userProjects()
    {
        return $this->userProjects;
    }

    public function withHasNotificationsForDataset($hasNotificationsForDataset)
    {
        $this->hasNotificationsForDataset = $hasNotificationsForDataset;
        return $this;
    }

    public function hasNotificationsForDataset()
    {
        return $this->hasNotificationsForDataset;
    }

    public function activityAttributeRoute($attrName)
    {
        return route('public.datasets.activity-attributes.show',
            [$this->dataset, 'attribute' => $attrName]);
    }

    public function entityAttributeRoute($attrName)
    {
        return route('public.datasets.entity-attributes.show',
            [$this->dataset, 'attribute' => $attrName]);
    }
}
