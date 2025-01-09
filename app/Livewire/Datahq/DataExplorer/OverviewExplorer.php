<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\DatahqInstance;
use App\Models\Experiment;
use App\Models\Project;
use Livewire\Component;

class OverviewExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;
    public ?Experiment $experiment = null;

    public $view = '';
    public $context = '';

    public function setView($view): void
    {
        $this->view = $view;
    }

    public function render()
    {
        return view('livewire.datahq.data-explorer.overview-explorer');
    }
}
