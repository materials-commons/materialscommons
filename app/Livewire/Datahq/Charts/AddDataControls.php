<?php

namespace App\Livewire\Datahq\Charts;

use Livewire\Component;
use function view;

class AddDataControls extends Component
{
    public $sampleAttributes;
    public $processAttributes;
    public string $eventName;

    public function render()
    {
        return view('livewire.datahq.charts.add-data-controls');
    }
}
