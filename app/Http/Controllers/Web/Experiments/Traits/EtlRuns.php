<?php

namespace App\Http\Controllers\Web\Experiments\Traits;

use App\Models\EtlRun;
use App\Models\Experiment;

trait EtlRuns
{
    public function etlRunsCount($experiment)
    {
        return EtlRun::where('etlable_type', Experiment::class)
                     ->where('etlable_id', $experiment->id)
                     ->count();
    }

    public function etlRuns($experiment)
    {
        return EtlRun::with('files')
                     ->where('etlable_type', Experiment::class)
                     ->where('etlable_id', $experiment->id)
                     ->get();
    }
}