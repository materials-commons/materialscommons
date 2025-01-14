<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\Experiment;
use App\Models\Project;
use Livewire\Component;

class SamplesExplorer extends Component
{
    public ?Project $project;
    public ?Experiment $experiment = null;

    public function render()
    {
        return view('livewire.datahq.data-explorer.samples-explorer');
    }
}
