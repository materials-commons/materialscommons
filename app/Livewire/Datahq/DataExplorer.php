<?php

namespace App\Livewire\Datahq;

use App\Models\DatahqInstance;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class DataExplorer extends Component
{
    private DatahqInstance $instance;
    public Project $project;
    public string $context = '';
    public string $view = '';
    public string $tab = '';
    public string $subview = '';


    public function mount(Project $project, string $context, string $view, string $tab, string $subview): void
    {
        $this->project = $project;
        $this->context = $context;
        $this->view = $view;
        $this->tab = $tab;
        $this->subview = $subview;
        $this->instance = DatahqInstance::getOrCreateActiveDatahqInstanceForUser(auth()->user(), $this->project);
    }

    #[On('reload-instance')]
    public function reloadInstance(): void
    {
        $this->instance = DatahqInstance::getOrCreateActiveDatahqInstanceForUser(auth()->user(), $this->project);
    }

    public function render()
    {
        ray("DataExplorer::render called");
        return view('livewire.datahq.data-explorer', [
            'instance' => $this->instance,
        ]);
    }
}
