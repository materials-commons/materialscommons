<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class SubviewState
{
    public string $name;
    public string $key;
    public string $viewType;
    public ?ViewStateData $viewData;

    public function __construct(string $name, string $key, string $viewType)
    {
        $this->name = $name;
        $this->key = $key;
        $this->viewType = $viewType;
        $this->viewData = null;
    }

    public static function makeKey(): string
    {
        return Uuid::uuid4()->toString();
    }
}