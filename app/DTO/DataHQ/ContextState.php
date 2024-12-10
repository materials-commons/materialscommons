<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;

class ContextState implements \JsonSerializable
{

    public Collection $tabs;

    public function __construct(Collection $tabs)
    {
        $this->tabs = $tabs;
    }

    public function jsonSerialize()
    {
        return [
            'tabs' => $this->tabs->map(function ($tab, $key) {
                return [$key => $tab->jsonSerialize()];
            })
        ];
    }
}