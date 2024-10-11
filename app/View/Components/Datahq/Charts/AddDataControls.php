<?php

namespace App\View\Components\Datahq\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddDataControls extends Component
{
    public $sampleAttributes;
    public $processAttributes;
    public string $eventName;

    public function __construct($sampleAttributes, $processAttributes, $eventName)
    {
        $this->sampleAttributes = $sampleAttributes;
        $this->processAttributes = $processAttributes;
        $this->eventName = $eventName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.charts.add-data-controls');
    }
}
