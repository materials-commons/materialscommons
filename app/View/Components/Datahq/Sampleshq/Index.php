<?php

namespace App\View\Components\Datahq\Sampleshq;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Request;

class Index extends Component
{
    public Project $project;

    public function __construct(Project $project)
    {
        ray("Initializing samplesHQ");
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $activeTab = Request::input('tab');
        $activeSubview = Request::input('subview');
        ray("activeSubview = {$activeSubview}");
        ray("activeTab = {$activeTab}");
        return view('components.datahq.sampleshq.index', [
            'activeTab'     => $activeTab,
            'activeSubview' => $activeSubview,
        ]);
    }
}
