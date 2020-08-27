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
        'doi', 'published_at', 'authors', 'file_selection',
        'owner_id', 'created_at', 'updated_at', 'files_count',
        'activities_count', 'entities_count', 'experiments_count',
        'comments_count', 'workflows_count',
    ];

    public function toArray($request)
    {
        $ds = $this->loadFromFields();

        if (!is_null($this['published_at'])) {
            $ds = $this->loadZipfileFields($ds);
            $ds['globus_path'] = $this['globus_path'];
        }

        if (!is_null($this['privately_published_at'])) {
            $ds['globus_path'] = $this['globus_path'];
        }

        return $ds;
    }

    private function loadZipfileFields(array $ds)
    {
        if (is_null($ds['published_at'])) {
            return $ds;
        }

        $zipfilePath = $this->zipfilePath();
        if (file_exists($zipfilePath)) {
            $ds['zipfile_size'] = filesize($zipfilePath);
        }

        return $ds;
    }
}
