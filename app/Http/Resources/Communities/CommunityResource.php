<?php

namespace App\Http\Resources\Communities;

use App\Http\Resources\Datasets\DatasetResource;
use App\Http\Resources\Files\FileResource;
use App\Http\Resources\JsonResource;
use App\Http\Resources\Links\LinkResource;

class CommunityResource extends JsonResource
{
    protected $fields = [
        'id', 'uuid', 'name', 'description', 'summary', 'public',
        'owner_id', 'created_at', 'updated_at', 'datasets_count',
        'files_count', 'links_count',
    ];

    public function toArray($request)
    {
        $data = $this->loadFromFields();
        $data['files'] = FileResource::collection($this->whenLoaded('files'));
        $data['links'] = LinkResource::collection($this->whenLoaded('links'));
        $data['datasets'] = DatasetResource::collection($this->whenLoaded('datasets'));
        return $data;
    }
}
