<?php

namespace App\Livewire\Datahq\DataExplorer\SamplesExplorer;

use App\DTO\DataHQ\ChartRequestDTO;
use Livewire\Component;

class DataControls extends Component
{
    public string $xAttrType = '';
    public string $xAttr = '';
    public string $yAttrType = '';
    public string $yAttr = '';

    public function addToChart()
    {
        $chartRequest = new ChartRequestDTO($this->xAttr, $this->yAttr, $this->xAttrType, $this->yAttrType);
        $this->dispatch('create-chart', $chartRequest);
    }

    public function render()
    {
        return view('livewire.datahq.data-explorer.samples-explorer.data-controls');
    }
}
