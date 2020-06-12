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
        'id', 'uuid', 'name', 'summary', 'description', 'owner_id',
        'created_at', 'updated_at', 'published_at', 'files_count',
        'activities_count', 'entities_count', 'experiments_count',
        'comments_count', 'workflows_count',
    ];

    public function toArray($request)
    {
        $ds = $this->loadFromFields();

        if (!is_null($this['published_at'])) {
            $ds = $this->loadZipfileFields($ds);
        }

        return $ds;
    }

    private function loadZipfileFields(array $ds)
    {
        $zipfilePath = $this->zipfilePath();
        if (file_exists($zipfilePath)) {
            $ds['zipfile_size'] = filesize($zipfilePath);
        }

        return $ds;
    }
}
