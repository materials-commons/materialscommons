<?php

namespace App\Actions\Directories;

use App\Helpers\PathHelpers;
use App\Models\File;

class CreateDirectoryAction
{
    /**
     * Create a directory
     *
     * @param $data
     * @return mixed
     */
    public function __invoke($data)
    {
        $data['owner_id'] = auth()->id();
        $data['mime_type'] = 'directory';
        $data['media_type_description'] = 'directory';
        $parent = File::findOrFail($data['directory_id']);
        $data['path'] = PathHelpers::normalizePath("{$parent->path}/{$data['name']}");
        $directory = File::create($data);
        return $directory;
    }
}
