<?php

namespace App\View\Components\Datahq\Explorer;

use App\DTO\DataHQ\SubviewState;
use App\Models\Project;
use App\Services\DataHQ\DataHQStateStoreInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowSubviewChart extends Component
{
    public Project $project;
    public string $stateService;
    public SubviewState $subviewState;
    public string $subview;
    private DataHQStateStoreInterface $stateStore;

    public function __construct(Project $project, string $subview, SubviewState $subviewState, string $stateService)
    {
        $this->project = $project;
        $this->stateService = $stateService;
        $this->subviewState = $subviewState;
        $this->subview = $subview;
        $this->stateStore = app($this->stateService);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datahq.explorer.show-subview-chart');
    }
}
