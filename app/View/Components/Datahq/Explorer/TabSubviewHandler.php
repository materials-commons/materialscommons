<?php

namespace App\View\Components\Datahq\Explorer;

use App\Models\Project;
use App\Services\DataHQ\DataHQStateStore;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TabSubviewHandler extends Component
{
    public Project $project;
    public string $tab;
    public string $subview;

    public function __construct(Project $project, string $tab, string $subview)
    {
        $this->project = $project;
        $this->tab = $tab;
        $this->subview = $subview;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $stateStore = DataHQStateStore::getState()->getContextStateStore();
        $state = $stateStore->getOrCreateState();
        $tabState = $state->getTabStateByKey($this->tab);
        $subviewState = $tabState->getSubviewStateByKey($this->subview);

        return view('components.datahq.explorer.tab-subview-handler', [
            'subviewState' => $subviewState,
        ]);
    }
}
