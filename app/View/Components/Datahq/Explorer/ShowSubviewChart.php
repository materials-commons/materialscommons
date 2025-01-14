<?php

namespace App\View\Components\Datahq\Explorer;

use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowSubviewChart extends Component
{
    use EntityAndAttributeQueries;

    public Project $project;
    public string $subview;

    public function __construct(Project $project, string $subview)
    {
        $this->project = $project;
        $this->subview = $subview;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.explorer.show-subview-chart', [
            'sampleAttributes'  => $this->getSampleAttributes($this->project->id),
            'processAttributes' => $this->getProcessAttributes($this->project->id),
        ]);
    }
}
