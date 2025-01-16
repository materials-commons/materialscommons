<?php

namespace App\Livewire\Datahq\DataExplorer\SamplesExplorer;

use App\Models\DatahqInstance;
use App\Models\Experiment;
use App\Models\Project;
use Livewire\Component;

class ViewControls extends Component
{
    public DatahqInstance $instance;
    public Project $project;
    public ?Experiment $experiment = null;

    public $showFilters = false;

    public $showProcessesTable = false;
    public $showSampleAttributesTable = false;
    public $showProcessAttributesTable = false;

    public function toggleProcesses()
    {
        $this->showProcessesTable = !$this->showProcessesTable;
    }

    public function toggleSampleAttributes()
    {
        $this->showSampleAttributesTable = !$this->showSampleAttributesTable;
    }

    public function toggleProcessAttributes()
    {
        $this->showProcessAttributesTable = !$this->showProcessAttributesTable;
    }

    public function render()
    {
        return view('livewire.datahq.data-explorer.samples-explorer.view-controls');
    }
}
