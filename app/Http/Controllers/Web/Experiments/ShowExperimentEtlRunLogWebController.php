<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\EtlRun;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowExperimentEtlRunLogWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Experiment $experiment, EtlRun $etlRun)
    {
        $content = Storage::disk('mcfs')->get("etl_logs/{$etlRun->uuid}.log");
        return "<pre>{$content}</pre>";
    }
}
