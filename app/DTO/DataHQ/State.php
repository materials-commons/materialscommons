<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;

class State
{
    public Collection $tabs;

    public function __construct()
    {
        $this->tabs = collect();
    }

    public function getTabStateByKey(string $key): ?TabState
    {
        foreach ($this->tabs as $tab) {
            if ($tab->key === $key) {
                return $tab;
            }
        }

        return null;
    }
}