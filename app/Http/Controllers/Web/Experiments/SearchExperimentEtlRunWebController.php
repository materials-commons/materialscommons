<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\EtlRun;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SearchExperimentEtlRunWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Experiment $experiment, EtlRun $etlRun)
    {
        $search = $request->get("search");
        if (blank($search)) {
            return $this->wholeFile($etlRun);
        }

        return $this->searchFile($etlRun, $search);
    }

    private function wholeFile(EtlRun $etlRun)
    {
        $content = Storage::disk('mcfs')->get("etl_logs/{$etlRun->uuid}.log");
        return "<pre>{$content}</pre>";
    }

    private function searchFile(EtlRun $etlRun, $search)
    {
        $searchTerms = explode(' ', Str::of($search)->lower());

        $fd = fopen(Storage::disk('mcfs')->path("etl_logs/{$etlRun->uuid}.log"), "r");
        if (!$fd) {
            return "<pre>Log doesn't exist</pre>";
        }

        $content = "";
        while (!feof($fd)) {
            $line = fgets($fd);
            if (Str::of($line)->lower()->containsAll($searchTerms)) {
                $content = $content.$line;
            }
        }

        fclose($fd);
        return "<pre>{$content}</pre>";
    }
}
