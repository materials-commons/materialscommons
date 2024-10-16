<?php

namespace App\View\Components\Datahq;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateChartModal extends Component
{
    public Project $project;
    public $sampleAttributes;
    public $processAttributes;
    public string $tab;
    public string $modalId;

    public string $stateService;

    public function __construct($project, $sampleAttributes, $processAttributes, $tab, $stateService, $modalId)
    {
        $this->project = $project;
        $this->sampleAttributes = $sampleAttributes;
        $this->processAttributes = $processAttributes;
        $this->tab = $tab;
        $this->stateService = $stateService;
        $this->modalId = $modalId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.create-chart-modal');
    }
}
