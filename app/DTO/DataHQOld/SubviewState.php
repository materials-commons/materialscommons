<?php

namespace App\DTO\DataHQOld;

use Livewire\Wireable;
use Ramsey\Uuid\Uuid;

class SubviewState implements Wireable
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


    public function toLivewire()
    {
        return [
            'name'     => $this->name,
            'key'      => $this->key,
            'viewType' => $this->viewType,
            'viewData' => $this->viewData,
        ];
    }

    public static function fromLivewire($value)
    {
        $name = $value['name'];
        $key = $value['key'];
        $viewType = $value['viewType'];
        return new static($name, $key, $viewType);
    }
}
