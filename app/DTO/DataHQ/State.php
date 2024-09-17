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
}