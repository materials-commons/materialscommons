<?php

namespace App\Http\Resources\Projects;

use App\Http\Resources\Entities\EntityResource;
use App\Http\Resources\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    protected $fields = [
        'id', 'uuid', 'name', 'description', 'owner_id', 'is_active',
        'created_at', 'updated_at', 'files_count', 'activities_count', 'entities_count',
        'workflows_count',
    ];

    public function toArray($request)
    {
        $data = $this->loadFromFields();
        $data['entities'] = EntityResource::collection($this->whenLoaded('entities'));
        return $data;
    }
}
