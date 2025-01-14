<?php

namespace App\DTO\DataHQ;

use JsonSerializable;

class Column implements JsonSerializable
{
    public string $name;  // Column name (title)
    public string $attribute; // Column attribute name
    public string $attributeType; // Column attribute type (entity or activity attribute)

    public function __construct(string $name, string $attribute, string $attributeType)
    {
        $this->name = $name;
        $this->attribute = $attribute;
        $this->attributeType = $attributeType;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['name'], $data['attribute'], $data['attributeType']);
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