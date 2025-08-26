<?php

namespace App\View\Components\Datasets;

use Illuminate\View\Component;

class ShowPublishedDate extends Component
{
    public $dataset;

    public function __construct($dataset)
    {
        $this->dataset = $dataset;
    }

    public function render()
    {
        return view('components.datasets.show-published-date');
    }

    public function publishedDate()
    {
        $publishedDate = null;
        if ($this->dataset->published_at) {
            $publishedDate = $this->dataset->published_at->diffForHumans();
        } elseif($this->dataset->test_published_at) {
            $publishedDate = $this->dataset->test_published_at->diffForHumans();
        }
        return $publishedDate ? "Published: {$publishedDate}" : "Not Published";
    }
}
