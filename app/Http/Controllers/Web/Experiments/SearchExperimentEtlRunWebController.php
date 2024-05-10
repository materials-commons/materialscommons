<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\EtlRun;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\SearchFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SearchExperimentEtlRunWebController extends Controller
{
    use SearchFile;

    public function __invoke(Request $request, Project $project, Experiment $experiment, EtlRun $etlRun)
    {
        $search = $request->get("search");
        return $this->search('mcfs', "etl_logs/{$etlRun->uuid}.log", $search);
    }
}
