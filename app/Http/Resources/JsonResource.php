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
            $data[$field] = $this->when(isset($this[$field]), $this[$field]);
        }

        return $data;
    }
}
