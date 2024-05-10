<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LogViewer extends Component
{
    public string $loadLogRoute;
    public string $searchLogRoute;

    public function __construct($loadLogRoute, $searchLogRoute)
    {
        $this->loadLogRoute = $loadLogRoute;
        $this->searchLogRoute = $searchLogRoute;
    }

    public function render(): View|Closure|string
    {
        return view('components.log-viewer');
    }
}
