<?php

namespace App\View\Components\Datasets;

use Illuminate\View\Component;

class ShowPublishedDate extends Component
{
    public $published;

    public function __construct($published)
    {
        $this->published = $published;
    }

    public function render()
    {
        return view('components.datasets.show-published-date');
    }

    public function publishedDate()
    {
        return $this->published ? $this->published->diffForHumans() : "Not Published";
    }
}
