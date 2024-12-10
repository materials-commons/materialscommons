<?php

namespace App\DTO\DataHQ;

use JsonSerializable;

class SubviewState2 implements JsonSerializable
{
    public string $type;
    public string $mql;
    public string $xAttrType;
    public string $xAttrName;
    public string $yAttrType;
    public string $yAttrName;

    public function __construct(
        string $type,
        string $mql,
        string $xAttrType,
        string $xAttrName,
        string $yAttrType,
        string $yAttrName
    ) {
        $this->type = $type;
        $this->mql = $mql;
        $this->xAttrType = $xAttrType;
        $this->xAttrName = $xAttrName;
        $this->yAttrType = $yAttrType;
        $this->yAttrName = $yAttrName;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'mql' => $this->mql,
            'xAttrType' => $this->xAttrType,
            'xAttrName' => $this->xAttrName,
            'yAttrType' => $this->yAttrType,
            'yAttrName' => $this->yAttrName,
        ];
    }
}