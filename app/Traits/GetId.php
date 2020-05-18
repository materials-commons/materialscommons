<?php

namespace App\Traits;

trait GetId
{
    public function getId($item)
    {
        return gettype($item) == "object" ? $item->id : $item;
    }
}
