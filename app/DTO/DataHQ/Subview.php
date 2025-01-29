<?php

namespace App\DTO\DataHQ;

use JsonSerializable;

class Subview implements JsonSerializable
{
    public string $name; // Name of the subview
    public string $description; // Description of the subview

    // A subview may only have a chart OR a table, but not both
    public ?Chart $chart; // The chart associated with the subview
    public ?Table $table; // The table associated with the subview

    public function __construct(string $name, string $description, ?Chart $chart, ?Table $table)
    {
        $this->name = $name;
        $this->description = $description;
        $this->chart = $chart;
        $this->table = $table;
    }

    public function jsonSerialize(): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'chart'       => $this->chart?->jsonSerialize(),
            'table'       => $this->table?->jsonSerialize(),
        ];
    }
}