<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;

class ContextState implements JsonSerializable
{

    public Collection $views;

    public function __construct(Collection $views)
    {
        $this->views = $views;
    }

    public function jsonSerialize(): array
    {
        return [
            'views' => $this->views->map(function ($view, $key) {
                return $view->jsonSerialize();
            })->toArray()
        ];
    }
}