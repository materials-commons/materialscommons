<?php

namespace App\View\Components\Datahq;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowNumericAttributeFilters extends Component
{
    public Project $project;
    public string $attrName;
    public string $attrType;
    public $attrDetails;

    public function __construct(Project $project, string $attrName, string $attrType, $attrDetails)
    {
        $this->project = $project;
        $this->attrName = $attrName;
        $this->attrType = $attrType;
        $this->attrDetails = $attrDetails;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.show-numeric-attribute-filters');
    }
}
