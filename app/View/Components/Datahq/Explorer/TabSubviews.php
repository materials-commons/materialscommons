<?php

namespace App\View\Components\Datahq\Explorer;

use App\Models\Project;
use App\Services\DataHQ\DataHQContextStateStoreInterface;
use App\Services\DataHQ\DataHQStateStore;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TabSubviews extends Component
{
    public Project $project;
    public string $tab;
    private DataHQContextStateStoreInterface $stateStore;

    public function __construct(Project $project, string $tab)
    {
        $this->project = $project;
        $this->tab = $tab;
        $this->stateStore = DataHQStateStore::getState()->getContextStateStore();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $state = $this->stateStore->getOrCreateState();
        $tabState = $state->getTabStateByKey($this->tab);
        return view('components.datahq.explorer.tab-subviews', [
            'subviews' => $tabState->subviews,
        ]);
    }
}
