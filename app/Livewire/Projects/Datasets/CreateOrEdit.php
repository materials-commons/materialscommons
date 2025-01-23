<?php

namespace App\Livewire\Projects\Datasets;

use Livewire\Component;

class CreateOrEdit extends Component
{
    public $activeTab = "required";

    public function render()
    {
        return view('livewire.projects.datasets.create-or-edit');
    }
}
