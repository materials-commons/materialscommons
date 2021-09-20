<?php

namespace App\Actions\Directories;

use App\Helpers\PathHelpers;
use App\Models\File;

class CreateDirectoryAction
{
    public function execute($data, $ownerId)
    {
        $data['owner_id'] = $ownerId;
        $data['mime_type'] = 'directory';
        $data['media_type_description'] = 'directory';
        $parent = File::findOrFail($data['directory_id']);
        $data['path'] = PathHelpers::normalizePath("{$parent->path}/{$data['name']}");

        $dir = $this->getExistingDirIfExists($data['path'], $parent->project_id);
        if (!is_null($dir)) {
            return $dir;
        }

        return File::create($data);
    }

    private function getExistingDirIfExists($path, $projectId)
    {
        return File::where('path', $path)
                   ->where('current', true)
                   ->where('project_id', $projectId)
                   ->first();
    }
}
