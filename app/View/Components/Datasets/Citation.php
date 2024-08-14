<?php

namespace App\View\Components\Datasets;

use App\Models\Dataset;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Citation extends Component
{
    public Dataset $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datasets.citation');
    }
}
