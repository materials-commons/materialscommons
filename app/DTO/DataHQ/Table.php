<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;

class Table implements JsonSerializable
{
    public string $name;  // The name of the table
    public string $description; // A description of the table
    public Collection $columns; // A list of columns for the table

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->columns = collect();
        foreach ($data['columns'] as $column) {
            $this->columns->push(new Column($column));
        }
        $this->columns = collect($data['columns']);
    }

    public function jsonSerialize(): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'columns'     => $this->columns->map(function ($column, $key) {
                return $column->jsonSerialize();
            })->toArray(),
        ];
    }
}