<?php

namespace App\Actions\Experiments;

use App\Enums\ExperimentStatus;
use App\Helpers\PathHelpers;
use App\Imports\Etl\EtlState;
use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\Experiment;
use App\Models\File;
use function array_key_exists;

class CreateExperimentAction
{
    public function __invoke($data, $sheet = null)
    {
        $experiment = new Experiment(['name' => $data['name'], 'project_id' => $data['project_id']]);
        if (array_key_exists('description', $data)) {
            $experiment->description = $data['description'];
        }
        if (array_key_exists('summary', $data)) {
            $experiment->summary = $data['summary'];
        }

        $experiment->owner_id = auth()->id();
        $experiment->status = ExperimentStatus::InProgress;
        if (!is_null($sheet)) {
            $experiment->sheet_id = $sheet->id;
        } elseif (isset($data['file_id'])) {
            $file = File::with('directory')
                        ->where("id", $data["file_id"])
                        ->where("project_id", $data["project_id"])
                        ->first();
            $experiment->loaded_file_path = PathHelpers::joinPaths($file->directory->path, $file->name);
        }
        $experiment->save();
        $experiment->refresh();

        $fileId = null;
        $sheetUrl = null;
        $file = null;
        if (array_key_exists('file_id', $data) && $data['file_id'] !== null) {
            $fileId = $data['file_id'];
            $file = File::with('directory')->find($fileId);
        } elseif (array_key_exists('sheet_url', $data) && $data['sheet_url'] !== null) {
            $sheetUrl = $data['sheet_url'];
        }

        if (!is_null($fileId) || !is_null($sheetUrl)) {
            $etlState = new EtlState(auth()->id(), $fileId);
            $experiment->etlruns()->save($etlState->etlRun);

            if (!is_null($fileId)) {
                $etlState->setSource(
                    'spreadsheet',
                    $file?->name,
                    $file?->fullPath()
                );
            } else {
                $etlState->setSource('google_sheet', 'Google Sheet', $sheetUrl);
            }

            $ps = new ProcessSpreadsheetJob($data['project_id'], $experiment->id, auth()->id(), $fileId, $sheetUrl, $etlState->etlRun->id);
            dispatch($ps)->onQueue('globus');
            $experiment->setRelation('queuedEtlRun', $etlState->etlRun);
        }

        return $experiment;
    }
}
