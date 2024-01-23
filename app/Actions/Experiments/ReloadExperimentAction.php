<?php

namespace App\Actions\Experiments;

use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function dirname;
use function parse_url;
use const PHP_URL_HOST;
use const PHP_URL_PATH;

class ReloadExperimentAction
{
    public function execute(Project $project, Experiment $experiment, $fileId, $sheetUrl, $userId)
    {
        try {
            DB::transaction(function () use ($project, $experiment, $fileId, $sheetUrl, $userId) {
                $experiment->activities()->delete();
                $experiment->entities()->delete();
                $experiment->files()->detach();
                $sheetUrl = $this->cleanupGoogleSheetUrl($sheetUrl);
                ProcessSpreadsheetJob::dispatch($project->id, $experiment->id, $userId, $fileId,
                    $sheetUrl)->onQueue('globus');
            });

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function cleanupGoogleSheetUrl($url): ?string
    {
        if (Str::contains($url, "/edit?")) {
            // Remove /edit from the end of the url
            $path = dirname(parse_url($url, PHP_URL_PATH));

            $host = parse_url($url, PHP_URL_HOST);

            // $path starts with a slash (/), so we don't add one to separate host and path when constructing the url.
            return "https://{$host}{$path}";
        }

        return $url;
    }
}