<?php

namespace App\View\Components\Mql\QueryBuilder;

use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Selectors extends Component
{
    use EntityAndAttributeQueries;

    public $project;
    public $category;
    public $processAttributes;
    public $sampleAttributes;
    public $activities;

    public function __construct($project, $category, $activities, $processAttributes, $sampleAttributes)
    {
        $this->project = $project;
        $this->category = $category;
        $this->activities = $activities;
        $this->processAttributes = $processAttributes;
        $this->sampleAttributes = $sampleAttributes;
    }

    public function render(): View|Closure|string
    {
        // For now these are passed in because the old query builder needs them. Once that is removed then this
        // component can retrieve them.
//        $this->processAttributes = $this->getProcessAttributes($this->project->id);
//        $this->sampleAttributes = $this->getSampleAttributes($this->project->id);
        return view('components.mql.query-builder.selectors');
    }
}
