<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;

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

    public function jsonSerialize(): array
    {
        return [
            'currentTab'     => $this->currentTab,
            'currentSubview' => $this->currentSubview,
            'contexts'       => [
                $this->contexts->map(function ($context, $key) {
                    return [$key => $context->jsonSerialize()];
                })
            ],
        ];
    }
}