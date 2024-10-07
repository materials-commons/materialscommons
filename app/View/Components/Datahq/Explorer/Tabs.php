<?php

namespace App\View\Components\Datahq\Explorer;

use App\Models\Project;
use App\Services\DataHQ\DataHQStateStore;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabs extends Component
{
    public string $stateService;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $dataHQState = DataHQStateStore::getState();
        $this->stateService = $dataHQState->stateContextService;
        $s = $dataHQState->getContextStateStore();
        $state = $s->getOrCreateState();
        return view('components.datahq.explorer.tabs', [
            'tabs' => $state->tabs,
        ]);
    }
}
