<?php

namespace App\Livewire\Datahq;

use App\Models\DatahqInstance;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class DataExplorer extends Component
{
    public DatahqInstance $instance;
    public Project $project;
    public string $tab = '';

    #[On('reload-instance')]
    public function reloadInstance(): void
    {
        $this->instance = DatahqInstance::getOrCreateActiveDatahqInstanceForUser(auth()->user(), $this->project);
    }

    public function render()
    {
        ray("DataExplorer render called");
        return view('livewire.datahq.data-explorer');
    }
}
