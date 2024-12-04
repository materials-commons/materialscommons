<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\DatahqInstance;
use App\Models\Project;
use Livewire\Attributes\Url;
use Livewire\Component;

class OverviewExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;

    #[Url(history: true)]
    public $tab = 'samples';

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
