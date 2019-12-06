<?php

namespace App\Actions\Experiments;

use App\Enums\ExperimentStatus;
use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\Experiment;

class CreateExperimentAction
{
    public function __invoke($data)
    {
        $experiment = new Experiment(['name' => $data['name'], 'project_id' => $data['project_id']]);
        if (array_key_exists('description', $data)) {
            $experiment->description = $data['description'];
        }
        $experiment->owner_id = auth()->id();
        $experiment->status = ExperimentStatus::InProgress;
        $experiment->save();
        $experiment->fresh();

        if (array_key_exists('file_id', $data) && $data['file_id'] !== null) {
            $ps = new ProcessSpreadsheetJob($data['project_id'], $experiment->id, auth()->id(), $data['file_id']);
            dispatch($ps);
        }

        return $experiment;
    }
}