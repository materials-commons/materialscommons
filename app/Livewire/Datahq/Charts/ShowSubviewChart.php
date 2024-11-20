<?php

namespace App\Livewire\Datahq\Charts;

use App\DTO\DataHQ\ChartRequestDTO;
use App\DTO\DataHQ\SubviewState;
use App\Models\Project;
use App\Traits\Charts\GetChartData;
use App\Traits\DataDictionaryQueries;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use function view;

class ShowSubviewChart extends Component
{
    use EntityAndAttributeQueries;
    use DataDictionaryQueries;
    use GetChartData;

    public Project $project;
    public SubviewState $subviewState;
    public string $subview;

    #[On('create-chart')]
    public function handleCreateChart($data)
    {
        $chartDataRequest = ChartRequestDTO::fromArray($data);

        $xattrValues = $this->getAttributeDataForProject($chartDataRequest->xattrType, $chartDataRequest->xattr,
            $this->project);
        $yattrValues = $this->getAttributeDataForProject($chartDataRequest->yattrType, $chartDataRequest->yattr,
            $this->project);

        $xyMatches = $this->createXYMatches($xattrValues->get($chartDataRequest->xattr),
            $yattrValues->get($chartDataRequest->yattr));
        $this->dispatch('add-data', $xyMatches);
    }

    public function render()
    {
        return view('livewire.datahq.charts.show-subview-chart', [
            'sampleAttributes'  => $this->getSampleAttributes($this->project->id),
            'processAttributes' => $this->getProcessAttributes($this->project->id),
        ]);
    }
}
