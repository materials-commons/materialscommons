<?php

namespace App\Http\Resources\Links;

use App\Http\Resources\JsonResource;

class LinkResource extends JsonResource
{
    protected $fields = [
        'id', 'uuid', 'name', 'description', 'summary', 'url',
        'owner_id', 'created_at', 'updated_at',
    ];

    public function toArray($request)
    {
        return $this->loadFromFields();
    }
}
