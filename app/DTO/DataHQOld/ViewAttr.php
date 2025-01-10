<?php

namespace App\DTO\DataHQOld;

class ViewAttr
{
    public string $attrType;
    public string $attrName;

    public function __construct(string $attrType, string $attrName)
    {
        $this->attrType = $attrType;
        $this->attrName = $attrName;
    }
}
