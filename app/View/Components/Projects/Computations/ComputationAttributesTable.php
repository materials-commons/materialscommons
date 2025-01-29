<?php

namespace App\View\Components\Projects\Computations;

use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function route;
use function view;

class ComputationAttributesTable extends Component
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
        return view('components.projects.samples.sample-attributes-table', [
            'entityAttributes' => $this->getUniqueEntityAttributesForProject($this->project->id, 'computational'),
        ]);
    }

    public function entityAttributeRoute($attrName)
    {
        return route('projects.entity-attributes.show', [$this->project, 'attribute' => $attrName]);
    }
}
