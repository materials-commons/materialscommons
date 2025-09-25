<?php

namespace App\DTO\Attributes;

class FlattenedAttribute
{
    public function __construct(
        public int $id,
        public string $name,
        public  array $values,
        public  int $entityStateId,
    ) {}

    /**
     * Get the first value or null if no values exist
     */
    public function getFirstValue(): mixed
    {
        return $this->values[0] ?? null;
    }

    /**
     * Get all values as a comma-separated string
     */
    public function getValuesAsString(): string
    {
        return implode(', ', $this->values);
    }

    /**
     * Check if attribute has multiple values
     */
    public function hasMultipleValues(): bool
    {
        return count($this->values) > 1;
    }

    /**
     * Get the value(s) - returns single value if only one, array if multiple
     */
    public function getValue(): mixed
    {
        return count($this->values) === 1 ? $this->values[0] : $this->values;
    }

    /**
     * Convert to array representation
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'values' => $this->values,
            'entity_state_id' => $this->entityStateId,
        ];
    }

    /**
     * Convert to key-value pair (name => value)
     */
    public function toKeyValuePair(): array
    {
        return [$this->name => $this->getValue()];
    }
}
