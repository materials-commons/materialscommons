<?php

namespace App\Http\Controllers\Web\Experiments;

trait EtlRunsCount
{
    public function getEtlRunsCount($etlRuns)
    {
        return $etlRuns->filter(function ($etlRun) {
            return isset($etlRun->files[0]);
        })->count();
    }
}