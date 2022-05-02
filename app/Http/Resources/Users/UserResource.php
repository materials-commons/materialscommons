<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\JsonResource;

class UserResource extends JsonResource
{
    protected $fields = [
        'id', 'uuid', 'name', 'email', 'description', 'affiliation', 'created_at',
        'updated_at', 'slug',
    ];

    public function toArray($request)
    {
        return $this->loadFromFields();
    }
}
