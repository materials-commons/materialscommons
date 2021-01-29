<?php

namespace App\Http\Resources\Datasets;

use App\Http\Resources\JsonResource;

class DatasetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    protected $fields = [
        'id', 'uuid', 'name', 'license', 'license_link', 'summary', 'description',
        'doi', 'published_at', 'authors', 'file_selection', 'globus_endpoint_id',
        'globus_path', 'owner_id', 'created_at', 'updated_at', 'files_count',
        'activities_count', 'entities_count', 'experiments_count', 'comments_count',
        'workflows_count', 'zipfile_size', 'tags',
    ];

    public function toArray($request)
    {
        $ds = $this->loadFromFields();

        if (!is_null($this['published_at'])) {
            $ds = $this->loadZipfileFields($ds);
        }

        $ds['tags'] = [];
        foreach ($this['tags'] as $tag) {
            array_push($ds['tags'], [
                'id'         => $tag['id'],
                'name'       => $tag['name'],
                'slug'       => $tag['slug'],
                'created_at' => $tag['created_at'],
                'updated_at' => $tag['updated_at'],
            ]);
        }

        return $ds;
    }

    private function loadZipfileFields(array $ds)
    {
        if (is_null($ds['published_at'])) {
            return $ds;
        }

        $ds['zipfile_name'] = $this->zipfileName();

        return $ds;
    }
}
