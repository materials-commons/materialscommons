<?php

namespace App\View\Components\Datahq\Explorer;

use App\Models\Project;
use App\Services\DataHQ\DataHQStateStoreInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TabSubviews extends Component
{
    public Project $project;
    public string $tab;
    private DataHQStateStoreInterface $stateStore;

    public function __construct(Project $project, string $tab, string $stateService)
    {
        $this->project = $project;
        $this->tab = $tab;
        $this->stateStore = app($stateService);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $state = $this->stateStore->getOrCreateStateForProject($this->project);
        $tabState = $state->getTabStateByKey($this->tab);
        ray("TabSubviews", $tabState);
        return view('components.datahq.explorer.tab-subviews', [
            'subviews' => $tabState->subviews,
        ]);
    }
}
