<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;

class TabState
{
    public string $name;
    public string $key;
    public Collection $subviews;

    public function __construct(string $name, string $key)
    {
        $this->name = $name;
        $this->key = $key;
        $this->subviews = collect();
    }

    public function getSubviewStateByKey(string $key): ?SubviewState
    {
        foreach ($this->subviews as $subview) {
            if ($subview->key === $key) {
                return $subview;
            }
        }

        return null;
    }
}