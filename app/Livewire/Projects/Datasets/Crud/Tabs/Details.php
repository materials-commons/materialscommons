<?php

namespace App\Livewire\Projects\Datasets\Crud\Tabs;

use App\Models\Community;
use Livewire\Component;

class Details extends Component
{
    public function render()
    {
        return view('livewire.projects.datasets.crud.tabs.details', [
            'communities' => Community::orderBy('name')->get(),
        ]);
    }
}
