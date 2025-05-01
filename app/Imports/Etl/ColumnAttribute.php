<?php

namespace App\Imports\Etl;

class ColumnAttribute
{
    public $columnNumber;
    public $name;
    public $value;
    public $type;
    public $unit;
    public $important;
    public $tags;
    public $group;

    public function __construct($name, $value, $unit, $type, $columnNumber, $important = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->unit = $unit;
        $this->type = $type;
        $this->columnNumber = $columnNumber;
        $this->important = $important;
        $this->tags = [];
        $this->group = null;
    }

    public function addTags($tags): ColumnAttribute
    {
        collect(explode(';', $tags))
            ->map(function ($tag) {
                return trim($tag);
            })
            ->each(function ($tag) {
                $this->tags[] = $tag;
            });
        return $this;
    }
}
