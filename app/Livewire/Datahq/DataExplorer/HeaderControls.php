<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\Experiment;
use App\Models\Project;
use Livewire\Component;

class HeaderControls extends Component
{
    public ?Project $project = null;
    public $selectedData = "";
    public $selectedExplorer = "";

    public function updatedSelectedData()
    {
        $this->dispatch('selected-data', $this->selectedData);
    }

    public function updatedSelectedExplorer()
    {
        $this->dispatch('selected-explorer', $this->selectedExplorer);
    }

    public function render()
    {
        $experiments = Experiment::where('project_id', $this->project->id)->get();
        return view('livewire.datahq.data-explorer.header-controls', [
            'experiments' => $experiments,
        ]);
    }
}
