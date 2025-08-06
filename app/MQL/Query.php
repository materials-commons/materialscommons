<?php

namespace App\MQL;

class Query
{
    public function __construct(
        private string $entityType,
        private array $filters,
        private array $selectFields
    ) {}

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getSelectFields(): array
    {
        return $this->selectFields;
    }
}
