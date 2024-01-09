<?php

namespace App\View\Components\Mql\QueryBuilder;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActivitySelector extends Component
{
    public $activities;
    public $category;
    public $project;

    public function __construct($project, $activities, $category)
    {
        $this->project = $project;
        $this->activities = $activities;
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mql.query-builder.activity-selector');
    }
}
