<?php

namespace App\Http\Resources\Experiments;

use App\Http\Resources\JsonResource;

class ExperimentResource extends JsonResource
{
    protected $fields = [
        'id', 'uuid', 'name', 'description', 'owner_id',
        'created_at', 'updated_at', 'activities_count', 'entities_count',
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->loadFromFields();

        return $data;
    }
}
