<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\DTO\DataHQ\Subview;
use App\DTO\DataHQ\View;
use App\Models\DatahqInstance;
use App\Models\Experiment;
use App\Models\Project;
use Livewire\Attributes\Url;
use Livewire\Component;
use function collect;

class SamplesExplorer extends Component
{
    public DatahqInstance $instance;
    public ?Project $project;
    public ?Experiment $experiment = null;
    public string $context;

    #[Url(history: true)]
    public string $view;
    public string $subview;

    public function addFilteredView()
    {
        $count = $this->instance->samples_explorer_state->views->count();
        $viewName = "Filtered View {$count}";
        $v = new View($viewName, "", "", "Samples", collect([
            new Subview("Samples", "", null, null),
        ]));
        $this->instance->samples_explorer_state->views->push($v);
        $this->instance->samples_explorer_state->currentView = $viewName;
        $this->instance->update([
            'samples_explorer_state' => $this->instance->samples_explorer_state,
        ]);
    }

    public function setView($view)
    {
        $this->view = $view;
        $this->instance->samples_explorer_state->currentView = $view;
        $this->instance->update([
            'samples_explorer_state' => $this->instance->samples_explorer_state,
        ]);
    }

    public function render()
    {
        $this->setFromInstance();

        $currentView = $this->instance->samples_explorer_state->views->first(function ($value) {
            return $this->instance->samples_explorer_state->currentView === $value->name;
        });

        $currentSubview = $currentView->subviews->first(function ($value) use ($currentView) {
            return $currentView->currentSubview === $value->name;
        });

        return view('livewire.datahq.data-explorer.samples-explorer', [
            'currentView'    => $currentView,
            'currentSubview' => $currentSubview,
        ]);
    }

    private function setFromInstance()
    {
        $this->view = $this->instance->samples_explorer_state->currentView;
    }
}
