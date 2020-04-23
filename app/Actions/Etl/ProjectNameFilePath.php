<?php

namespace App\Actions\Etl;

trait ProjectNameFilePath
{
    /**
     * To remain compatible with the old api paths are assumed to start with the project name. This action
     * will remove the project name and just keep the starting slash as names are stored with a / for the
     * the root rather than the project name.
     * @param $path string Path containing project name
     * @param $projectNameLength int Length of project name
     * @return mixed
     */
    public function removeProjectNameFromPath($path, $projectNameLength)
    {
        return substr_replace($path, '', 0, $projectNameLength);
    }
}

