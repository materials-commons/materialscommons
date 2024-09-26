<?php

namespace App\View\Components\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowScatterChart extends Component
{
//    public array $xData;
//    public string $xLabel;
//    public array $yData;
//    public string $yLabel;
//    public string $title;

    public function __construct()
    {
//        $this->xData = $xData;
//        $this->yData = $yData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.charts.show-scatter-chart');
    }
}
