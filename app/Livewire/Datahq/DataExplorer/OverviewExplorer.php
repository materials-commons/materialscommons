<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\DatahqInstance;
use App\Models\Project;
use Livewire\Component;

class OverviewExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;

    public $tab = 'samples';
    public $context = 'project';

    public function setTab($tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        ray("OverviewExplorer::render called");
        return view('livewire.datahq.data-explorer.overview-explorer');
    }
}
