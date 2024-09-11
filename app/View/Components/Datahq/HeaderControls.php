<?php

namespace App\View\Components\Datahq;

use App\Models\Experiment;
use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderControls extends Component
{
    public ?Project $project = null;

    public function __construct(?Project $project = null)
    {
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $experiments = Experiment::where('project_id', $this->project->id)->get();
        return view('components.datahq.header-controls', [
            'experiments' => $experiments,
        ]);
    }
}
