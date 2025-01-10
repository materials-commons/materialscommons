<?php

namespace App\DTO\DataHQOld;

use Illuminate\Support\Collection;
use JsonSerializable;

class ViewState implements JsonSerializable
{
    public Collection $subviews;

    public function __construct($subviews)
    {
        $this->subviews = $subviews;
    }

    public function jsonSerialize(): array
    {
        return [
            'subviews' => $this->subviews->map(function ($subview, $key) {
                return $subview->jsonSerialize();
            })->toArray()
        ];
    }
}
