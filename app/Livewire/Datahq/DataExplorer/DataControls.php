<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\DTO\DataHQ\ChartRequestDTO;
use Livewire\Component;

class DataControls extends Component
{
    public $sampleAttributes;
    public $processAttributes;
    public string $xAttrType = '';
    public string $xAttr = '';
    public string $yAttrType = '';
    public string $yAttr = '';

    public function addToChart()
    {
        $chartRequest = new ChartRequestDTO($this->xAttr, $this->xAttrType, $this->yAttr, $this->yAttrType);
        $this->dispatch('set-chart-data', $chartRequest);
    }

    public function render()
    {
        return view('livewire.datahq.data-explorer.data-controls');
    }

    public function allAttrsSet(): bool
    {
        return !blank($this->xAttrType) && !blank($this->xAttr) && !blank($this->yAttrType) && !blank($this->yAttr);
    }
}
