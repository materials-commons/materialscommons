<?php

namespace App\View\Components\Datasets;

use App\Models\Dataset;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowCitations extends Component
{
    public Dataset $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function render(): View|Closure|string
    {
        $citations = $this->dataset->getCitations();
        return view('components.datasets.show-citations', [
            'hasCitations' => !is_null($citations),
            'citations'    => $citations,
        ]);
    }
}
