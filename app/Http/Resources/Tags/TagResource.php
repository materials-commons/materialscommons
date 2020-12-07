<?php

namespace App\Http\Resources\Tags;

use App\Http\Resources\JsonResource;

class TagResource extends JsonResource
{
    protected $fields = ['id', 'created_at', 'updated_at'];

    public function toArray($request)
    {
        $data = $this->loadFromFields();
        $data['name'] = $this['name'];
        $data['slug'] = $this['slug'];
        return $data;
    }
}
