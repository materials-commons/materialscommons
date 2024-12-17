<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\Experiment;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class HeaderControls extends Component
{
    public ?Project $project = null;
    public $selectedData = "";
    public $selectedExplorer = "";

    #[On('selected-data')]
    public function handleSelectedData($selectedData): void
    {
        ray("handleSelectedData: {$selectedData}");
        $this->selectedData = $selectedData;
    }

    #[On('selected-explorer')]
    public function handleSelectedExplorer($selectedExplorer): void
    {
        $this->selectedExplorer = $selectedExplorer;
        /*
         * $context = $request->input('context', 'project');
        $explorer = $request->input('explorer', 'overview');
        $view = $request->input('view', 'samples');
        $subview = $request->input('subview', '');
         */
        $this->redirectRoute('projects.datahq.index', [
            $this->project,
            'explorer' => $selectedExplorer,
            'context'  => Request()->input('context', 'project'),
            'view'     => Request()->input('view', 'samples'),
        ]);
//        $this->dispatch('reload-selected-explorer', $selectedExplorer);
    }

    public function render()
    {
        $experiments = Experiment::where('project_id', $this->project->id)->get();
        return view('livewire.datahq.data-explorer.header-controls', [
            'experiments' => $experiments,
        ]);
    }
}
