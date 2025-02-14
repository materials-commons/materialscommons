<?php

namespace App\Livewire\Projects\Datasets\Crud;

use Livewire\Component;

class Authors extends Component
{
    public $dataset;

    public function render()
    {
        return view('livewire.projects.datasets.crud.authors');
    }
}
