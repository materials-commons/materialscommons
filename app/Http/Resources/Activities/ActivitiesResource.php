<?php

namespace App\Http\Resources\Activities;

use App\Http\Resources\JsonResource;

class ActivitiesResource extends JsonResource
{
    protected $fields = ['id', 'uuid', 'name', 'description'];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->loadFromFields();
        return $data;
    }
}
