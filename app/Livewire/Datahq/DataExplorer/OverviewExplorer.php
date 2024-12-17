<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\DatahqInstance;
use App\Models\Experiment;
use App\Models\Project;
use Livewire\Attributes\On;
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

    #[On('reload-overview-explorer')]
    public function handleReloadOverviewExplorer($context): void
    {
        $this->context = $context;
        ray("handleReloadOverviewExplorer: {$context}");
        $this->dispatch('reload-component');
    }

    public function render()
    {
        return view('livewire.datahq.data-explorer.overview-explorer');
    }
}
