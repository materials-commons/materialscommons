<?php

namespace App\View\Components\Datahq;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateTable extends Component
{
    public Project $project;
    public $sampleAttributes;
    public $processAttributes;
    public string $modalId;

    public function __construct($project, $sampleAttributes, $processAttributes, $modalId)
    {
        $this->project = $project;
        $this->sampleAttributes = $sampleAttributes;
        $this->processAttributes = $processAttributes;
        $this->modalId = $modalId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.create-table');
    }
}
