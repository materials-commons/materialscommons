<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;

class Explorer implements JsonSerializable
{
    public string $explorerType;  // Type of explorer (overview, samples explorer, computations explorer, processes explorer)
    public string $currentView;   // For this explorer which view is current
    public Collection $views; // The list of views associated with this explorer

    public function __construct(string $explorerType, string $currentView, Collection $views)
    {
        $this->explorerType = $explorerType;
        $this->currentView = $currentView;
        $this->views = $views;
    }

    public function jsonSerialize(): array
    {
        return [
            'explorerType' => $this->explorerType,
            'currentView'  => $this->currentView,
            'views'        => $this->views->map(function ($view, $key) {
                return $view->jsonSerialize();
            })->toArray(),
        ];
    }
}