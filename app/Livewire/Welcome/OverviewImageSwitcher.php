<?php

namespace App\Livewire\Welcome;

use Livewire\Component;

class OverviewImageSwitcher extends Component
{
    public $showImage = "organize";

    public function render()
    {
        return view('livewire.welcome.overview-image-switcher');
    }

    public function switchToImage($image): void
    {
        $this->showImage = $image;
    }
}
