<?php

namespace App\Livewire\Projects\Datasets\Crud;

use Livewire\Component;

class CreateDataset extends Component
{
    public $activeTab = "details";

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.projects.datasets.crud.create-dataset');
    }
}
