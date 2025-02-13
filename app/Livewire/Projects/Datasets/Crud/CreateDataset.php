<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Models\Dataset;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreateDataset extends Component
{
    public Dataset $dataset;

    #[Url(keep: true)]
    public $datasetId = '';

    #[Url(keep: true)]
    public $activeTab = "details";

    public function mount(Dataset $dataset)
    {
        $this->dataset = $dataset;
        ray("mount: {$dataset->id}");
        $this->datasetId = $dataset->id;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.projects.datasets.crud.create-dataset');
    }
}
