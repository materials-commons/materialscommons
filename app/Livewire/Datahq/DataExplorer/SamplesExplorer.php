<?php

namespace App\Livewire\Datahq\DataExplorer;

use App\DTO\DataHQ\Chart;
use App\DTO\DataHQ\ChartRequestDTO;
use App\DTO\DataHQ\Subview;
use App\DTO\DataHQ\View;
use App\Models\DatahqInstance;
use App\Models\Experiment;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use function collect;
use function uuid;

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

    public function addChart()
    {
        $currentView = $this->getCurrentView();
        $subviewCount = $currentView->subviews->count();
        $chartName = "Chart {$subviewCount}";
        $currentView->currentSubview = $chartName;
        $currentView->subviews->push(new Subview($chartName, "", new Chart("Chart"), null));
        $this->instance->update([
            'samples_explorer_state' => $this->instance->samples_explorer_state,
        ]);
    }

    #[On('set-chart-data')]
    public function handleSetChartData($data)
    {
        $chartRequestDTO = ChartRequestDTO::fromArray($data);
        // Persist the chart data for the current subview
        $currentView = $this->getCurrentView();
        $currentSubview = $this->getCurrentSubviewForView($currentView);
        $currentSubview->chart->xAxisAttribute = $chartRequestDTO->xattr;
        $currentSubview->chart->xAxisAttributeType = $chartRequestDTO->xattrType;
        $currentSubview->chart->yAxisAttribute = $chartRequestDTO->yattr;
        $currentSubview->chart->yAxisAttributeType = $chartRequestDTO->yattrType;
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

    public function setSubview($subviewName)
    {
        // Find the current view, then set that views current subview to the given subview name
        foreach ($this->instance->samples_explorer_state->views as $view) {
            if ($view->name === $this->instance->samples_explorer_state->currentView) {
                $view->currentSubview = $subviewName;
            }
        }
        $this->instance->update([
            'samples_explorer_state' => $this->instance->samples_explorer_state,
        ]);
    }

    public function render()
    {
        $this->view = $this->instance->samples_explorer_state->currentView;

        $currentView = $this->getCurrentView();
        $currentSubview = $this->getCurrentSubviewForView($currentView);

        return view('livewire.datahq.data-explorer.samples-explorer', [
            'currentView'    => $currentView,
            'currentSubview' => $currentSubview,
            'dataKey' => uuid(),
        ]);
    }

    function getCurrentView(): View
    {
        return $this->instance->samples_explorer_state->views->first(function ($value) {
            return $this->instance->samples_explorer_state->currentView === $value->name;
        });
    }

    function getCurrentSubviewForView(View $currentView): Subview
    {
        return $currentView->subviews->first(function ($value) use ($currentView) {
            return $currentView->currentSubview === $value->name;
        });
    }

    function getSubviewForView(View $currentView, string $subviewName): Subview
    {
        return $currentView->subviews->first(function ($value) use ($subviewName) {
            return $subviewName === $value->name;
        });
    }
}
