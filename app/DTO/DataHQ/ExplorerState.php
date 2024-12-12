<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;
use function collect;

class ExplorerState implements JsonSerializable
{
    public string $currentView;
    public string $currentSubview;
    public Collection $contexts;

    public function __construct(string $currentView, string $currentSubview, Collection $contexts)
    {
        $this->currentView = $currentView;
        $this->currentSubview = $currentSubview;
        $this->contexts = $contexts;
    }

    public static function createDefaultExplorerState(): ExplorerState
    {
        $contexts = collect([
            "project"        => new ContextState(collect([
                "All Samples"               => new ViewState(collect([
                    "All"    => new SubviewState2("chart", "some mql", "sample", "alloy", "sample", "hardness"),
                    "Chart1" => new SubviewState2("chart", "more mql", "process", "p1", "process", "p2"),
                ])),
                "Samples Filtered By Alloy" => new ViewState(collect([
                    "All"     => new SubviewState2("chart", "some mql", "sample", "alloy2", "sample", "hardness2"),
                    "Chart11" => new SubviewState2("chart", "more mql", "process", "p11", "process", "p22"),
                ])),
            ])),
            "experiment-123" => new ContextState(collect([
                "All Samples"               => new ViewState(collect([
                    "All"    => new SubviewState2("chart", "some mql", "sample", "alloy", "sample", "hardness"),
                    "Chart1" => new SubviewState2("chart", "more mql", "process", "p1", "process", "p2"),
                ])),
                "Samples Filtered By Alloy" => new ViewState(collect([
                    "All"     => new SubviewState2("chart", "some mql", "sample", "alloy2", "sample", "hardness2"),
                    "Chart11" => new SubviewState2("chart", "more mql", "process", "p11", "process", "p22"),
                ])),
            ])),
        ]);
        return new ExplorerState("All Samples", "All", $contexts);
    }

    public function jsonSerialize(): array
    {
        return [
            'currentView' => $this->currentView,
            'currentSubview' => $this->currentSubview,
            'contexts' => $this->contexts->map(function ($context, $key) {
                return $context->jsonSerialize();
            })->toArray(),
        ];
    }
}