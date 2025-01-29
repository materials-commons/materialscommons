<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\DTO\DataHQ\Chart;
use App\DTO\DataHQ\ChartRequestDTO;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Charts\GetChartData;
use App\Traits\DataDictionaryQueries;
use App\Traits\Entities\EntityAndAttributeQueries;
use Livewire\Attributes\On;
use Livewire\Component;
use function view;

class DisplayChart extends Component
{
    use EntityAndAttributeQueries;
    use DataDictionaryQueries;
    use GetChartData;

    public Project $project;
    public ?Experiment $experiment;
    public ?Chart $chart = null;

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
//        $this->dispatch('add-data', $xyMatches);
    }

    public function render()
    {
        $xyMatches = null;
        if (!$this->isEmptyChart()) {
            if (is_null($this->experiment)) {
                $xyMatches = $this->createChartForProject();
            } else {
                $xyMatches = $this->createChartForExperiment();
            }
        }

        if (is_null($this->experiment)) {
            $sampleAttributes = $this->getSampleAttributes($this->project->id);
            $processAttributes = $this->getProcessAttributes($this->project->id);
        } else {
            $sampleAttributes = $this->getSampleAttributesForExperiment($this->experiment);
            $processAttributes = $this->getProcessAttributesForExperiment($this->experiment);
        }


        return view('livewire.datahq.data-explorer.display-chart', [
            'sampleAttributes'  => $sampleAttributes,
            'processAttributes' => $processAttributes,
            'chartData'         => $xyMatches,
        ]);
    }

    public function createChartForProject()
    {
        $xattrValues = $this->getAttributeDataForProject($this->chart->xAxisAttributeType, $this->chart->xAxisAttribute,
            $this->project);
        $yattrValues = $this->getAttributeDataForProject($this->chart->yAxisAttributeType, $this->chart->yAxisAttribute,
            $this->project);

        return $this->createXYMatches($xattrValues->get($this->chart->xAxisAttribute),
            $yattrValues->get($this->chart->yAxisAttribute));
    }

    public function createChartForExperiment()
    {
        $xattrValues = $this->getAttributeDataForExperiment($this->chart->xAxisAttributeType,
            $this->chart->xAxisAttribute,
            $this->experiment);
        $yattrValues = $this->getAttributeDataForExperiment($this->chart->yAxisAttributeType,
            $this->chart->yAxisAttribute,
            $this->experiment);

        return $this->createXYMatches($xattrValues->get($this->chart->xAxisAttribute),
            $yattrValues->get($this->chart->yAxisAttribute));
    }

    private function isEmptyChart(): bool
    {
        if (is_null($this->chart)) {
            return true;
        }

        if (blank($this->chart->xAxisAttribute) || blank($this->chart->yAxisAttribute)) {
            return true;
        }

        return false;
    }
}
