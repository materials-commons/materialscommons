<?php

namespace App\View\Components\Datasets;

use App\Models\Dataset;
use App\Traits\FileView;
use Illuminate\View\Component;

class ShowOverviewFiles extends Component
{
    use FileView;

    public Dataset $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }


    public function render()
    {
        return view('components.datasets.show-overview-files');
    }
}
