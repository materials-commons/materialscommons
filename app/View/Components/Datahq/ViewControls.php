<?php

namespace App\View\Components\Datahq;

use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ViewControls extends Component
{
    use EntityAndAttributeQueries;

    public Project $project;

    public bool $showFilters;

    public string $tab;

    public string $stateService;

    public function __construct(Project $project, string $tab, string $stateService, $showFilters = false)
    {
        $this->project = $project;
        $this->showFilters = $showFilters;
        $this->tab = $tab;
        $this->stateService = $stateService;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.view-controls', [
            'sampleAttributes'  => $this->getSampleAttributes($this->project->id),
            'processAttributes' => $this->getProcessAttributes($this->project->id),
        ]);
    }
}
