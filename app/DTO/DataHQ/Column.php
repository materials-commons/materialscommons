<?php

namespace App\DTO\DataHQ;

use JsonSerializable;

class Column implements JsonSerializable
{
    public string $name;  // Column name (title)
    public string $attribute; // Column attribute name
    public string $attributeType; // Column attribute type (entity or activity attribute)

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->attribute = $data['attribute'];
        $this->attributeType = $data['attributeType'];
    }

    public function jsonSerialize(): array
    {
        return [
            'name'          => $this->name,
            'attribute'     => $this->attribute,
            'attributeType' => $this->attributeType,
        ];
    }
}