<?php

namespace App\Actions\Files;

class CreateFilesForCommunityAction
{
    public function execute($community, $files)
    {
        $fileEntries = array();
        $createFileForCommunityAction = new CreateFileForCommunityAction();
        foreach ($files as $file) {
            $fileEntry = $createFileForCommunityAction->execute($community, '', '', $file);
            array_push($fileEntries, $fileEntry);
        }

        return $fileEntries;
    }
}