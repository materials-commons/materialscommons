<?php

namespace App\View\Components\Datahq\Sampleshq;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Request;

class TabViewHandler extends Component
{
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
        $tab = Request::input('tab');
        $subview = Request::input('subview');
        $showFilters = true;
        if (is_null($tab)) {
            $tab = "index";
        }

        if ($tab === 'index') {
            $showFilters = false;
        }

        if (is_null($subview)) {
            $subview = "index";
        }

        return view('components.datahq.sampleshq.tab-view-handler', [
            'tab'         => $tab,
            'subview'     => $subview,
            'showFilters' => $showFilters,
        ]);
    }
}