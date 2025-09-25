<?php

namespace App\MQL;

use App\Models\Activity;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class QueryExecutor
{
    // Query: is the parsed MQL query.
    // context: is the context of the query (currently either project or dataset).
    // id: is the id of the project or dataset.
    // The context and id are used to determine the scope of the query.
    public function execute(Query $query, $context, $id): Collection
    {
        $builder = $this->getBaseQuery($query->getEntityType());

        match ($context) {
            'project' => $builder->where('project_id', $id),
            'dataset' => $builder->where('dataset_id', $id),
            default => throw new \InvalidArgumentException("Unknown context: {$context}")
        };

        // Apply filters
        foreach ($query->getFilters() as $index => $filter) {
            $this->applyFilter($builder, $filter, $index === 0);
        }

        // Apply attribute sorting if specified

        // Get results
        $results = $builder->get();

        // Apply field selection if specified
        if (!empty($query->getSelectFields()) && !in_array('*', $query->getSelectFields())) {
            return $results->map(function ($item) use ($query) {
                $selected = [];
                foreach ($query->getSelectFields() as $field) {
                    $selected[$field] = $item->$field ?? null;
                }
                return (object) $selected;
            });
        }

        return $results;
    }

    private function getBaseQuery(string $entityType): Builder
    {
        return match ($entityType) {
            'samples' => Entity::where('category', 'experimental'),
            'processes' => Activity::query(),
            'computations' => Entity::where('category', 'computational'),
            default => throw new \InvalidArgumentException("Unknown entity type: {$entityType}")
        };
    }

    private function applyFilter(Builder $builder, Filter $filter, bool $isFirst): void
    {
        $field = $filter->getField();
        $operator = $filter->getOperator();
        $value = $filter->getValue();

        // Convert operator aliases
        $operator = match ($operator) {
            '<>' => '!=',
            default => $operator
        };

        // Apply the filter
        if ($isFirst) {
            $this->addWhereClause($builder, $field, $operator, $value);
        } else {
            // Use the logical operator from the previous filter
            // Note: This is a simplified approach - in a more complex parser,
            // you'd want to handle operator precedence properly
            $this->addWhereClause($builder, $field, $operator, $value);
        }
    }

    private function addWhereClause(Builder $builder, string $field, string $operator, string $value): void
    {
        if ($this->isAttributeField($field)) {
            $this->addAttributeWhereClause($builder, $field, $operator, $value);
        } else {
            $this->addModelWhereClause($builder, $field, $operator, $value);
        }
    }

    private function addAttributeWhereClause(Builder $builder, string $field, string $operator, string $value): void
    {
        // TODO: This is different depending on whether we are querying an entity or an activity. This version only handles Entity models.
        $attributeField = $this->getAttributeField($field);
        $builder->whereHas('entityStates.attrs', function ($q) use ($attributeField, $operator, $value) {
            $q->where('name', $attributeField)
              ->whereHas('values', function ($valueQuery) use ($operator, $value) {
                  $valueQuery->where('val->value', $operator, $value);
              });
        });
    }

    private function addModelWhereClause(Builder $builder, string $field, string $operator, string $value): void
    {
        switch ($operator) {
            case 'like':
                $builder->where($field, 'like', "%{$value}%");
                break;
            case 'in':
                $values = array_map('trim', explode(',', $value));
                $builder->whereIn($field, $values);
                break;
            default:
                $builder->where($field, $operator, $value);
        }
    }

    private function isAttributeField(string $field): bool
    {
        return Str::startsWith($field, 'attr.');
    }

    private function getAttributeField(string $field): string
    {
        return substr($field, 5);
    }
}
