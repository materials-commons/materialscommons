<?php

namespace App\Livewire\Datahq\Networkhq;

use App\Models\Project;
use Livewire\Component;

class Index extends Component
{
    public Project $project;

    public function render()
    {
        return view('livewire.datahq.networkhq.index');
    }
}
