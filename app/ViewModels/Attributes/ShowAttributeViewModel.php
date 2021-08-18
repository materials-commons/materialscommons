<?php

namespace App\ViewModels\Attributes;

use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Spatie\ViewModels\ViewModel;

class ShowAttributeViewModel extends ViewModel
{
    use AttributeStatistics;

    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Experiment */
    private $experiment;

    private $attributeValues;

    private $attributeName;

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

    public function withAttributeValues($attributeValues)
    {
        ray($attributeValues);
        $this->attributeValues = $attributeValues;
        return $this;
    }

    public function withAttributeName($name)
    {
        ray("withAttributeName", $name);
        $this->attributeName = $name;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function experiment()
    {
        return $this->experiment;
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
