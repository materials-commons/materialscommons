<?php

namespace App\Livewire\Datahq;

use App\Models\DatahqInstance;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class DataExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;
    public ?Experiment $experiment = null;

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
        $this->context = $selectedData;
        $this->dispatch("reload-{$this->explorer}-explorer", $this->context);
    }

    public function render()
    {
        if (Str::startsWith($this->context, 'e-')) {
            $experimentId = Str::after($this->context, 'e-');
            $this->experiment = Experiment::find($experimentId);
        }
        return view('livewire.datahq.data-explorer');
    }
}
