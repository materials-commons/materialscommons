<?php

namespace App\MQL;

use App\MQL\Exceptions\QueryParseException;

class QueryParser
{
    public function parse(string $queryContent): Query
    {
        $lines = array_map('trim', explode("\n", trim($queryContent)));
        $lines = array_filter($lines, fn($line) => !empty($line));

        if (empty($lines)) {
            throw new QueryParseException('Query cannot be empty');
        }

        // First line should be the entity type
        $firstLine = strtolower(array_shift($lines));
        if (!in_array($firstLine, ['samples', 'processes', 'computations'])) {
            throw new QueryParseException('Query must start with "samples", "processes", or "computations"');
        }

        // Last line should be the select statement
        $lastLine = array_pop($lines);
        if (!str_starts_with(strtolower($lastLine), 'select ')) {
            throw new QueryParseException('Query must end with a select statement');
        }

        $selectFields = $this->parseSelectFields($lastLine);

        // Remaining lines are filters
        $filters = [];
        foreach ($lines as $line) {
            $filters[] = $this->parseFilter($line);
        }

        return new Query($firstLine, $filters, $selectFields);
    }

    private function parseSelectFields(string $selectLine): array
    {
        $selectPart = trim(substr($selectLine, 6)); // Remove 'select '
        return array_map('trim', explode(',', $selectPart));
    }

    private function parseFilter(string $filterLine): Filter
    {
        // Parse: where <what> <operator> <value> [and|or]
        if (!str_starts_with(strtolower($filterLine), 'where ')) {
            throw new QueryParseException("Filter must start with 'where': {$filterLine}");
        }

        $filterPart = trim(substr($filterLine, 5)); // Remove 'where '

        // Check for logical operator at the end
        $logicalOperator = null;
        if (preg_match('/\s+(and|or)\s*$/i', $filterPart, $matches)) {
            $logicalOperator = strtolower($matches[1]);
            $filterPart = trim(str_replace($matches[0], '', $filterPart));
        }

        // Parse the main filter: <what> <operator> <value>
        if (!preg_match('/^(\w+)\s*(=|!=|<>|<=|>=|<|>|like|in)\s*(.+)$/i', $filterPart, $matches)) {
            throw new QueryParseException("Invalid filter format: {$filterLine}");
        }

        $field = trim($matches[1]);
        $operator = strtolower(trim($matches[2]));
        $value = trim($matches[3], '"\'');

        return new Filter($field, $operator, $value, $logicalOperator);
    }
}
