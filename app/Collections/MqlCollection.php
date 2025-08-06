<?php

namespace App\Collections;

use Illuminate\Support\Collection;

class MqlCollection extends Collection
{
    /**
     * Methods that accept closures and are not allowed in MQL
     */
    protected $restrictedMethods = [
        'each', 'filter', 'first', 'flatMap', 'groupBy', 'keyBy', 'map',
        'mapInto', 'mapSpread', 'mapToGroups', 'mapWithKeys', 'partition',
        'pipe', 'reduce', 'reject', 'sortBy', 'sortByDesc', 'tap', 'times',
        'transform', 'unless', 'unlessEmpty', 'unlessNotEmpty', 'when',
        'whenEmpty', 'whenNotEmpty'
    ];

    /**
     * Create a new collection instance
     */
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * Add the whereNested functionality
     */
    public function whereNested($key, $operator, $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        return $this->where(function ($item) use ($key, $operator, $value) {
            $itemValue = data_get($item, $key);

            switch ($operator) {
                case '=':
                case '==':
                    return $itemValue == $value;
                case '===':
                    return $itemValue === $value;
                case '!=':
                case '<>':
                    return $itemValue != $value;
                case '!==':
                    return $itemValue !== $value;
                case '<':
                    return $itemValue < $value;
                case '>':
                    return $itemValue > $value;
                case '<=':
                    return $itemValue <= $value;
                case '>=':
                    return $itemValue >= $value;
                default:
                    return false;
            }
        });
    }

    /**
     * Override __call to block restricted methods
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->restrictedMethods)) {
            throw new \BadMethodCallException("Method '{$method}' is not allowed in MQL queries. This method requires a function parameter.");
        }

        $result = parent::__call($method, $parameters);

        // Return MqlCollection instances for chainability
        if ($result instanceof Collection && !($result instanceof static)) {
            return new static($result->all());
        }

        return $result;
    }

    /**
     * Override methods that return collections to maintain type
     */
    public function where($key, $operator = null, $value = null)
    {
        $result = parent::where($key, $operator, $value);
        return new static($result->all());
    }

    public function chunk($size)
    {
        $chunks = [];
        foreach (parent::chunk($size) as $chunk) {
            $chunks[] = new static($chunk->all());
        }
        return new static($chunks);
    }

    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    public function take($limit)
    {
        if ($limit < 0) {
            return new static(array_slice($this->items, $limit, abs($limit)));
        }
        return new static(array_slice($this->items, 0, $limit));
    }
}
