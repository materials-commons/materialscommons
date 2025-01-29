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
    public ?DatahqInstance $instance;
    public ?Project $project;
    public ?Experiment $experiment = null;

    #[Url(history: true)]
    public string $context = '';

    #[Url(history: true)]
    public string $view = '';

    #[Url(history: true)]
    public string $explorer = '';

    #[Url(history: true)]
    public string $subview = '';

    #[On('selected-explorer')]
    public function handleSelectedExplorer($selectedExplorer): void
    {
        $this->explorer = $selectedExplorer;
        switch ($selectedExplorer) {
            case 'samples':
                if (is_null($this->instance->samples_explorer_state)) {
                    $this->instance->update([
                        'samples_explorer_state' => DatahqInstance::createDefaultSamplesExplorerState(),
                        'current_explorer'       => 'samples',
                    ]);
                } else {
                    $this->instance->update([
                        'current_explorer' => 'samples',
                    ]);
                }
                $this->view = $this->instance->samples_explorer_state->currentView;
                $this->subview = '';
                break;
            case 'overview':
                $this->view = $this->instance->overview_explorer_state->currentView;
                $this->instance->update([
                    'current_explorer' => 'overview',
                ]);
                if (!blank($this->subview)) {
                    $this->subview = '';
                }
                break;
            default:
                break;
        }

    }

    #[On('selected-data')]
    public function handleSelectedData($selectedData): void
    {
        $this->context = $selectedData;
        if (!Str::startsWith($this->context, 'e-')) {
            $this->experiment = null;
        } else {
            $experimentId = Str::after($this->context, 'e-');
            $this->experiment = Experiment::find($experimentId);
        }

        $this->getInstance();
    }

    public function render()
    {
        if (Str::startsWith($this->context, 'e-')) {
            if (is_null($this->experiment) || $this->experiment->id !== Str::after($this->context, 'e-')) {
                $experimentId = Str::after($this->context, 'e-');
                $this->experiment = Experiment::find($experimentId);
            }
        }

        $idPartOfKey = "";
        if (!is_null($this->project)) {
            $idPartOfKey .= "p-{$this->project->id}";
        }

        if (!is_null($this->experiment)) {
            $idPartOfKey .= "e-{$this->experiment->id}";
        }

        return view('livewire.datahq.data-explorer', [
            'key' => "{$this->context}-{$this->explorer}{$idPartOfKey}",
        ]);
    }

    private function getInstance()
    {
        $this->instance->update(['current_at' => null]);
        if (is_null($this->experiment)) {
            $this->instance = DatahqInstance::getOrCreateInstanceForProject($this->project, auth()->user());
        } else {
            $this->instance = DatahqInstance::getOrCreateInstanceForExperiment($this->experiment, auth()->user());
        }

        $this->instance->update(['current_at' => now()]);
    }
}
