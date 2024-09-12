<?php

namespace App\View\Components\Projects\Processes;

use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function route;

class ProcessAttributesTable extends Component
{
    use DataDictionaryQueries;
    use AttributeStatistics;

    public Project $project;

    /**
     * Create a new component instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.projects.processes.process-attributes-table', [
            'activityAttributes' => $this->getUniqueActivityAttributesForProject($this->project->id)
        ]);
    }

    public function activityAttributeRoute($attrName)
    {
        return route('projects.activity-attributes.show', [$this->project, 'attribute' => $attrName]);
    }
}
