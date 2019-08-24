<?php

namespace App\Http\Resources\Activities;

use App\Http\Resources\JsonResource;

class ActivityResource extends JsonResource
{
    protected $fields = ['id', 'uuid', 'name', 'description', 'created_at', 'updated_at', 'files_count', 'entities_count', 'activities_count'];

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
