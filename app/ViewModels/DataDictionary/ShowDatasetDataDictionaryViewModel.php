<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Dataset;
use App\Models\Project;

class ShowDatasetDataDictionaryViewModel extends AbstractShowDataDictionaryViewModel
{
    /** @var \App\Models\Project */
    private $project;

    /** @var \App\Models\Dataset */
    private $dataset;

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function withDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function project()
    {
        return $this->project;
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
