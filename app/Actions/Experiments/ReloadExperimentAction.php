<?php

namespace App\Actions\Experiments;

use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ReloadExperimentAction
{
    public function execute(Project $project, Experiment $experiment, $fileId, $userId)
    {
        try {
            DB::transaction(function () use ($project, $experiment, $fileId, $userId) {
                $experiment->activities()->delete();
                $experiment->entities()->delete();
                ProcessSpreadsheetJob::dispatch($project->id, $experiment->id, $userId, $fileId)->onQueue('globus');
            });

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}