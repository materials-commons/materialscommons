<?php

namespace App\View\Components\Datahq\Sampleshq;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TabViewHandler extends Component
{
    public Project $project;
    public string $activeTab;
    public string $activeSubview;

    public function __construct(Project $project, string $activeTab, string $activeSubview)
    {
        $this->project = $project;
        $this->activeTab = $activeTab;
        $this->activeSubview = $activeSubview;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $showFilters = true;

        if ($this->activeTab === 'index') {
            $showFilters = false;
        }

        return view('components.datahq.sampleshq.tab-view-handler', [
            'showFilters' => $showFilters,
        ]);
    }
}
