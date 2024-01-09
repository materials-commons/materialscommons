<?php

namespace App\View\Components\Mql\QueryBuilder;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActivityAttributeSelector extends Component
{
    public $processAttributes;
    public $project;
    public $category;

    public function __construct($processAttributes, $project, $category)
    {
        $this->processAttributes = $processAttributes;
        $this->project = $project;
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mql.query-builder.activity-attribute-selector');
    }
}
