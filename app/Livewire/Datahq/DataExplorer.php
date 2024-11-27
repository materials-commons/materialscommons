<?php

namespace App\Livewire\Datahq;

use App\Models\DatahqInstance;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class DataExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;
    public $tab;

    public function render()
    {
        return view('livewire.datahq.data-explorer');
    }
}
