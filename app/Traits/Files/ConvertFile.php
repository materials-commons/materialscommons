<?php

namespace App\Traits\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Jobs\Files\GenerateThumbnailJob;
use App\Models\Script;

trait ConvertFile
{
    private function runTriggersAndBackgroundJobs($file): void
    {
        if ($file->shouldBeConverted()) {
            ConvertFileJob::dispatch($file)->onQueue('globus');
        }

        if ($file->isImage()) {
            GenerateThumbnailJob::dispatch($file)->onQueue('globus');
        }

        if ($file->isRunnable()) {
            Script::createScriptForFileIfNeeded($file);
        }

//        $this->fireTriggers($fileEntry);
    }

//    private function fireTriggers(File $file)
//    {
//        foreach ($this->triggers as $trigger) {
//            if ($trigger->fileWillActivateTrigger($file)) {
//                $trigger->execute();
//            }
//        }
//    }
}
