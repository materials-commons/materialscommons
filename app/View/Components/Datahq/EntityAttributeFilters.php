<?php

namespace App\View\Components\Datahq;

use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EntityAttributeFilters extends Component
{
    use DataDictionaryQueries;
    use AttributeStatistics;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.entity-attribute-filters', [
            'entityAttributes' => $this->getUniqueEntityAttributesForProject($this->project->id, 'experimental'),
        ]);
    }
}
