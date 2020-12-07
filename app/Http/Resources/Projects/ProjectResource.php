<?php

namespace App\Http\Resources\Projects;

use App\Http\Resources\Entities\EntityResource;
use App\Http\Resources\Files\FileResource;
use App\Http\Resources\JsonResource;
use App\Http\Resources\Users\UserResource;

class ProjectResource extends JsonResource
{
    protected $fields = [
        'id', 'uuid', 'name', 'description', 'owner_id', 'is_active',
        'created_at', 'updated_at', 'files_count', 'activities_count', 'entities_count',
        'workflows_count',
    ];

    public function toArray($request)
    {
        $data = $this->loadFromFields();
        $data['entities'] = EntityResource::collection($this->whenLoaded('entities'));
        $data['rootDir'] = new FileResource($this->whenLoaded('rootDir'));
        if (isset($this['team']['admins'])) {
            $data['admins'] = UserResource::collection($this['team']['admins']);
        }

        if (isset($this['team']['members'])) {
            $data['members'] = UserResource::collection($this['team']['members']);
        }
        return $data;
    }
}
