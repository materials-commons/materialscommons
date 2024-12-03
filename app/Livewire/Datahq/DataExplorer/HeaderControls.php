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
    public $selectedView = "";

    #[On('selected-data')]
    public function handleSelectedData($selectedData): void
    {
        ray("handleSelectedData: {$selectedData}");
        $this->selectedData = $selectedData;
    }

    #[On('selected-view')]
    public function handleSelectedView($selectedView): void
    {
        $this->selectedView = $selectedView;
        ray("handleSelectedView: {$selectedView}");
        // This will need to be changed, based on the selected data. Either redirect for project or experiment? Or the
        // route could determine if this is project or experiment based.
//        $this->redirectRoute('projects.datahq.sampleshq.index',
//            [$this->project, 'tab' => 'index', 'subview' => 'index']);
        $this->dispatch('reload-instance');
    }

    public function render()
    {
        $experiments = Experiment::where('project_id', $this->project->id)->get();
        return view('livewire.datahq.data-explorer.header-controls', [
            'experiments' => $experiments,
        ]);
    }
}
