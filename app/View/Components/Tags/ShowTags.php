<?php

namespace App\View\Components\Tags;

use Illuminate\View\Component;

class ShowTags extends Component
{
    private $tags;

    public function __construct($tags)
    {
        $this->tags = $tags;
    }

    public function render()
    {
        return view('components.tags.show-tags', ['tags' => $this->tags]);
    }
}
