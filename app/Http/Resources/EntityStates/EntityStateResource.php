<?php

namespace App\Http\Resources\EntityStates;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
