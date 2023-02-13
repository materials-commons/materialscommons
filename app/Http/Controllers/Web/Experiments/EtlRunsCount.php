<?php

namespace App\Http\Controllers\Web\Experiments;

trait EtlRunsCount
{
    public function getEtlRunsCount($etlRuns)
    {
        return $etlRuns->count();
    }
}