<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LogViewer extends Component
{
    public string $loadLogRoute;
    public string $searchLogRoute;

    public ?string $setLogLevelRoute;

    public function __construct($loadLogRoute, $searchLogRoute, $setLogLevelRoute = null)
    {
        $this->loadLogRoute = $loadLogRoute;
        $this->searchLogRoute = $searchLogRoute;
        $this->setLogLevelRoute = $setLogLevelRoute;
    }

    public function render(): View|Closure|string
    {
        return view('components.log-viewer');
    }
}
