<?php

namespace App\View\Components\Mql;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QueryBuilder extends Component
{
    public $category;
    public $activities;
    public $project;
    public $processAttributes;
    public $sampleAttributes;
    public $processAttributeDetails;
    public $sampleAttributeDetails;

    public function __construct($category, $activities, $project, $processAttributes, $sampleAttributes,
                                $processAttributeDetails, $sampleAttributeDetails)
    {
        $this->category = $category;
        $this->activities = $activities;
        $this->project = $project;
        $this->processAttributes = $processAttributes;
        $this->sampleAttributes = $sampleAttributes;
        $this->processAttributeDetails = $processAttributeDetails;
        $this->sampleAttributeDetails = $sampleAttributeDetails;
    }

    public function render(): View|Closure|string
    {
        return view('components.mql.query-builder');
    }
}
