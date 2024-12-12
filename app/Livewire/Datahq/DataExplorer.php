<?php

namespace App\Livewire\Datahq;

use App\Models\DatahqInstance;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class DataExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;

    #[Url(history: true)]
    public string $context = '';

    #[Url(history: true)]
    public string $view = '';

    #[Url(history: true)]
    public string $explorer = '';

    #[Url(history: true)]
    public string $subview = '';

    #[On('reload-instance')]
    public function reloadInstance($selectedExplorer): void
    {
        $this->explorer = $selectedExplorer;
    }

    #[On('selected-data')]
    public function handleSelectedData($selectedData): void
    {
        ray("handleSelectedData: {$selectedData}");
        $this->context = $selectedData;
    }

    public function render()
    {
        ray("DataExplorer::render called");
        return view('livewire.datahq.data-explorer');
    }
}
