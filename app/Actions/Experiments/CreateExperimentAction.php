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
        if (array_key_exists('summary', $data)) {
            $experiment->summary = $data['summary'];
        }
        $experiment->owner_id = auth()->id();
        $experiment->status = ExperimentStatus::InProgress;
        $experiment->save();
        $experiment->refresh();

        return $experiment;
    }
}
