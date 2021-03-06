<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\JsonResource;

class UserApiTokenResource extends JsonResource
{
    protected $fields = [
        'api_token', 'email',
    ];

    public function toArray($request)
    {
        return $this->loadFromFields();
    }
}
