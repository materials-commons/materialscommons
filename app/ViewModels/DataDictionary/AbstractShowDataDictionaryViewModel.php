<?php

namespace App\ViewModels\DataDictionary;

use Spatie\ViewModels\ViewModel;

abstract class AbstractShowDataDictionaryViewModel extends ViewModel
{
    use AttributeStatistics;

    abstract public function activityAttributeRoute($attrName);

    abstract public function entityAttributeRoute($attrName);

    protected $entityAttributes;
    protected $activityAttributes;

    public function withEntityAttributes($entityAttributes)
    {
        $this->entityAttributes = $entityAttributes;
        return $this;
    }

    public function withActivityAttributes($activityAttributes)
    {
        $this->activityAttributes = $activityAttributes;
        return $this;
    }

    public function entityAttributes()
    {
        return $this->entityAttributes;
    }

    public function activityAttributes()
    {
        return $this->activityAttributes;
    }
}
