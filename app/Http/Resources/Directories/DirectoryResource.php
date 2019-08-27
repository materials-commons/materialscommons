<?php

namespace App\Http\Resources\Directories;

use App\Http\Resources\JsonResource;

class DirectoryResource extends JsonResource
{
    protected $fields = [
        'id', 'uuid', 'name', 'path', 'description', 'created_at', 'updated_at',
        'owner_id', 'directory_id', 'mime_type', 'media_type_description',
        'files_count', 'entities_count', 'activities_count'
    ];

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
