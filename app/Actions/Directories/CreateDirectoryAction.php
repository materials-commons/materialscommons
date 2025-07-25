<?php

namespace App\Actions\Directories;

use App\Helpers\PathHelpers;
use App\Models\File;
use Exception;

class CreateDirectoryAction
{
    public function execute($data, $ownerId)
    {
        $data['owner_id'] = $ownerId;
        $data['mime_type'] = 'directory';
        $data['current'] = true;
        $data['media_type_description'] = 'directory';
        $parent = File::findOrFail($data['directory_id']);
        $data['path'] = PathHelpers::normalizePath("{$parent->path}/{$data['name']}");

        $dir = $this->getExistingDirIfExists($data['path'], $parent->project_id);
        if (!is_null($dir)) {
            return $dir;
        }

        try {
            return File::create($data);
        } catch (Exception $e) {
            // If we are here, then the directory already exists. This can happen because of a race
            // condition, when two threads/processes try to create the same directory at the same time.
            return $this->getExistingDirIfExists($data['path'], $parent->project_id);
        }
    }

    private function getExistingDirIfExists($path, $projectId)
    {
        return File::where('path', $path)
                   ->where('mime_type', 'directory')
                   ->where('current', true)
                   ->where('project_id', $projectId)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->first();
    }
}
