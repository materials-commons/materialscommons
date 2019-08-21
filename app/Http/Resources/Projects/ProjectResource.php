<?php

namespace App\Http\Resources\Projects;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    private $fields = [
        'id', 'name', 'description', 'uuid', 'owner_id', 'is_active',
        'created_at', 'updated_at'
    ];

    public function toArray($request)
    {
        $data = $this->loadFromFields();
        $data['counts'] = [
            'files' => $this->files_count,
            'activities' => $this->activities_count,
            'entities' => $this->entities_count,
        ];
        return $data;
    }

    protected function loadFromFields()
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->whenField($this[$field]);
        }

        return $data;
    }

    protected function whenField($value)
    {
        return $this->when($value, $value);
    }
}
