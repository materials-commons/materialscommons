<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;

class TabState
{
    public string $name;
    public string $key;
    public Collection $views;

    public function __construct(string $name, string $key)
    {
        $this->name = $name;
        $this->key = $key;
        $this->views = collect();
    }
}