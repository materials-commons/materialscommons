<?php

namespace App\Livewire\Projects\Datasets\Crud\Tabs;

use App\Livewire\Forms\DatasetForm;
use App\Models\Community;
use App\Models\Dataset;
use Livewire\Component;

class Details extends Component
{
    public DatasetForm $form;

    public $showSuccess = false;

    public function mount(Dataset $dataset)
    {
        $this->form->setDataset($dataset);
    }

    public function save()
    {
        $this->form->update();
        $this->showSuccess = true;
    }

    public function setTags($tags)
    {
        if (empty($tags)) {
            return;
        }

        $changed = collect(json_decode($tags))->pluck('value')->toArray();
        ray($changed);
    }

    public function render()
    {
        return view('livewire.projects.datasets.crud.tabs.details', [
            'communities' => Community::orderBy('name')->get(),
        ]);
    }
}
