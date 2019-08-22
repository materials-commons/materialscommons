<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    protected $fields = [];
    protected $includes = [];

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    protected function loadFromFields()
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->whenField($this[$field]);
        }

        return $data;
    }

    protected function whenField($value)
    {
        return $this->when($value, $value);
    }
}
