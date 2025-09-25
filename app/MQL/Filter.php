<?php

namespace App\MQL;

class Filter
{
    public function __construct(
        private string $field,
        private string $operator,
        private string $value,
        private ?string $logicalOperator = null
    ) {}

    public function getField(): string
    {
        return $this->field;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLogicalOperator(): ?string
    {
        return $this->logicalOperator;
    }
}
