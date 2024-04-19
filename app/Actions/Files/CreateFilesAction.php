<?php

namespace App\Actions\Files;

class CreateFilesAction
{
    public function __invoke($projectId, $dir, $files)
    {
        $fileEntries      = array();
        $createFileAction = new CreateFileAction();

        foreach ($files as $file) {
            $fileEntry = $createFileAction($projectId, $dir, '', $file);
            array_push($fileEntries, $fileEntry);
        }

        return $fileEntries;
    }
}