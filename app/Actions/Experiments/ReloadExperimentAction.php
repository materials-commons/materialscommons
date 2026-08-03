<?php

namespace App\Actions\Experiments;

use App\Imports\Etl\EtlState;
use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Traits\GoogleSheets;
use Illuminate\Support\Facades\DB;

class ReloadExperimentAction
{
    use GoogleSheets;

    public function execute(Project $project, Experiment $experiment, $fileId, $sheetUrl, $userId)
    {
        try {
            return DB::transaction(function () use ($project, $experiment, $fileId, $sheetUrl, $userId) {
                $experiment->activities()->delete();
                $experiment->entities()->delete();
                $experiment->files()->detach();
                $sheetUrl = $this->cleanupGoogleSheetUrl($sheetUrl);

                $etlState = new EtlState($userId, $fileId);
                $experiment->etlruns()->save($etlState->etlRun);

                if (!is_null($fileId)) {
                    $file = File::with('directory')->find($fileId);

                    $etlState->setSource(
                        'spreadsheet',
                        $file?->name,
                        $file?->fullPath()
                    );
                } else {
                    $etlState->setSource('google_sheet', 'Google Sheet', $sheetUrl);
                }
                ProcessSpreadsheetJob::dispatch($project->id, $experiment->id, $userId, $fileId,
                    $sheetUrl, $etlState->etlRun->id)->onQueue('globus');
                return $etlState->etlRun;
            });


        } catch (\Throwable $e) {
            return null;
        }
    }
}
