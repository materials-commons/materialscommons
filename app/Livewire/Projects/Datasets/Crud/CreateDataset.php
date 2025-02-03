<?php

namespace App\Livewire\Projects\Datasets\Crud;

use Livewire\Attributes\Url;
use Livewire\Component;

class CreateDataset extends Component
{

    #[Url('activeTab')]
    public $activeTab = "details";

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.projects.datasets.crud.create-dataset');
    }
}
