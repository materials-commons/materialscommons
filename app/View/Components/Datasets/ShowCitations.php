<?php

namespace App\View\Components\Datasets;

use App\Models\Dataset;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
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
        $hasCitations = false;
        $citations = null;
        if (Storage::disk('mcfs')->exists("__published_datasets/{$this->dataset->uuid}/citations.json")) {
            $hasCitations = true;
            $citationsFilePath = Storage::disk('mcfs')->path("__published_datasets/{$this->dataset->uuid}/citations.json");
            $citations = json_decode(file_get_contents($citationsFilePath), false);
        }
        return view('components.datasets.show-citations', [
            'hasCitations' => $hasCitations,
            'citations'    => $citations,
        ]);
    }
}
