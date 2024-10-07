<?php

namespace App\View\Components\Datahq\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddDataControls extends Component
{
    public $sampleAttributes;
    public $processAttributes;
    public string $callback;

    public function __construct($sampleAttributes, $processAttributes, $callback)
    {
        $this->sampleAttributes = $sampleAttributes;
        $this->processAttributes = $processAttributes;
        $this->callback = $callback;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.charts.add-data-controls');
    }
}
