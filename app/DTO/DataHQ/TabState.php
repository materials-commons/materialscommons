<?php

namespace App\DTO\DataHQ;

class TabState
{
    public string $name;
    public string $key;

    public function __construct(string $name, string $key)
    {
        $this->name = $name;
        $this->key = $key;
    }
}