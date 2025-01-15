<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\Models\Experiment;
use App\Models\Project;
use Livewire\Component;

// TODO: Perhaps this should be a blade component rather than a livewire component?
class OverviewExplorer extends Component
{
    public Project $project;
    public ?Experiment $experiment = null;

    public $view = '';
    public $context = '';

    public function render()
    {
        return view('livewire.datahq.data-explorer.overview-explorer');
    }
}
