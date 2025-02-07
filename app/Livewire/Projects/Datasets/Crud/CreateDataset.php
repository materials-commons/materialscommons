<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Models\Dataset;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreateDataset extends Component
{
    public ?Dataset $dataset;

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
