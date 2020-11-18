<?php

namespace App\View\Components;

use App\Models\File;
use App\ViewModels\Files\FileView;
use Illuminate\View\Component;

class DisplayFile extends Component
{
    use FileView;

    public $file;
    public $displayRoute;

    public function __construct(File $file, $displayRoute)
    {
        $this->file = $file;
        $this->displayRoute = $displayRoute;
    }

    public function render()
    {
        return view('components.display-file');
    }
}
