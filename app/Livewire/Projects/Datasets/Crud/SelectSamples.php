<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Models\Project;
use Livewire\Component;

class SelectSamples extends Component
{
    public function render()
    {
        return view('livewire.projects.datasets.crud.select-samples', [
            'project' => Project::findOrFail(278),
        ]);
    }
}
