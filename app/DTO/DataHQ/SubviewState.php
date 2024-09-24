<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;

class SubviewState
{
    public string $name;
    public string $key;
    public string $viewType;
    public Collection $viewData;

    public function __construct(string $name, string $key, string $viewType)
    {
        $this->name = $name;
        $this->key = $key;
        $this->viewType = $viewType;
        $this->viewData = collect();
    }
}