<?php

namespace App\Actions\Files;

use App\Models\ScriptTrigger;

class CreateFilesAction
{
    public function __invoke($project, $dir, $files)
    {
        $triggers = ScriptTrigger::getProjectTriggers($project);
        $fileEntries      = array();
        $createFileAction = new CreateFileAction($triggers);

        foreach ($files as $file) {
            $fileEntry = $createFileAction($project, $dir, '', $file);
            $fileEntries[] = $fileEntry;
        }

        return $fileEntries;
    }
}