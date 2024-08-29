<?php

namespace App\ViewModels\Attributes;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Spatie\ViewModels\ViewModel;
use function route;

class ShowAttributeViewModel extends ViewModel
{
    use AttributeStatistics;

    private $project = null;

    private $experiment = null;

    private $dataset = null;

    private $attributeValues;

    private $attributeName;
    private $entityRouteName;
    private $activityRouteName;

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function withExperiment(Experiment $experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    public function withDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function withAttributeValues($attributeValues)
    {
        $this->attributeValues = $attributeValues;
        return $this;
    }

    public function withAttributeName($name)
    {
        $this->attributeName = $name;
        return $this;
    }

    public function withEntityRouteName($routeName)
    {
        $this->entityRouteName = $routeName;
        return $this;
    }

    public function withActivityRouteName($routeName)
    {
        $this->activityRouteName = $routeName;
        return $this;
    }

    public function entityRoute($attrId)
    {
        if (isset($this->project)) {
            return route($this->entityRouteName, [$this->project, $attrId]);
        } else {
            // Dataset route
            return route($this->entityRouteName, [$this->dataset, $attrId]);
        }
    }

    public function activityRoute($attrId)
    {
        if (isset($this->project)) {
            return route($this->activityRouteName, [$this->project, $attrId]);
        } else {
            // Dataset route
            return route($this->activityRouteName, [$this->dataset, $attrId]);
        }
    }

    public function project()
    {
        return $this->project;
    }

    public function experiment()
    {
        return $this->experiment;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function attributeValues()
    {
        return $this->attributeValues;
    }

    public function attributeName()
    {
        return $this->attributeName;
    }

    public function value($attrVal)
    {
        $val = json_decode($attrVal, true);
        if (is_numeric($val["value"])) {
            return is_float($val["value"] + 0) ? (float) ($val["value"]) : (int) ($val["value"]);
        }
        return $val["value"];
    }
}
