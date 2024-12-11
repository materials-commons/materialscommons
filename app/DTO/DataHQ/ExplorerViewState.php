<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;
use function collect;

class ExplorerViewState implements JsonSerializable
{
    public string $currentTab;
    public string $currentSubview;
    public Collection $contexts;

    public function __construct(string $currentTab, string $currentSubview, Collection $contexts)
    {
        $this->currentTab = $currentTab;
        $this->currentSubview = $currentSubview;
        $this->contexts = $contexts;
    }

    public static function createDefaultExplorerState(): ExplorerViewState
    {
        $contexts = collect([
            "project"        => new ContextState(collect([
                "All Samples"               => new TabState2(collect([
                    "All"    => new SubviewState2("chart", "some mql", "sample", "alloy", "sample", "hardness"),
                    "Chart1" => new SubviewState2("chart", "more mql", "process", "p1", "process", "p2"),
                ])),
                "Samples Filtered By Alloy" => new TabState2(collect([
                    "All"     => new SubviewState2("chart", "some mql", "sample", "alloy2", "sample", "hardness2"),
                    "Chart11" => new SubviewState2("chart", "more mql", "process", "p11", "process", "p22"),
                ])),
            ])),
            "experiment-123" => new ContextState(collect([
                "All Samples"               => new TabState2(collect([
                    "All"    => new SubviewState2("chart", "some mql", "sample", "alloy", "sample", "hardness"),
                    "Chart1" => new SubviewState2("chart", "more mql", "process", "p1", "process", "p2"),
                ])),
                "Samples Filtered By Alloy" => new TabState2(collect([
                    "All"     => new SubviewState2("chart", "some mql", "sample", "alloy2", "sample", "hardness2"),
                    "Chart11" => new SubviewState2("chart", "more mql", "process", "p11", "process", "p22"),
                ])),
            ])),
        ]);
        return new ExplorerViewState("All Samples", "All", $contexts);
    }

    public function jsonSerialize(): array
    {
        return [
            'currentTab'     => $this->currentTab,
            'currentSubview' => $this->currentSubview,
            'contexts' => $this->contexts->map(function ($context, $key) {
                return $context->jsonSerialize();
            })->toArray(),
        ];
    }
}