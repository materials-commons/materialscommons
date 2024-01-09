<?php

namespace App\View\Components\Mql\QueryBuilder;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EntityAttributeSelector extends Component
{
    public $entityAttributes;
    public $project;
    public $category;

    public function __construct($entityAttributes, $project, $category)
    {
        $this->entityAttributes = $entityAttributes;
        $this->project = $project;
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mql.query-builder.entity-attribute-selector');
    }
}
