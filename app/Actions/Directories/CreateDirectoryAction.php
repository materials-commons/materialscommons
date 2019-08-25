<?php

namespace App\Actions\Directories;

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
        $data['media_type'] = 'directory';
        $directory = File::create($data);
        return $directory;
    }
}
