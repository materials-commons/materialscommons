<?php

namespace App\Imports\Etl;

use App\Models\EtlRun;
use Illuminate\Support\Facades\Storage;

class EtlState
{
    /** @var \App\Models\EtlRun */
    public $etlRun;

    public function __construct($ownerId, $fileId)
    {
        $this->etlRun = EtlRun::make([
            'file_id'                     => $fileId,
            'owner_id'                    => $ownerId,
            // activities
            'n_activities'                => 0,
            'n_activity_attributes'       => 0,
            'n_activity_attribute_values' => 0,

            // entities
            'n_entities'                  => 0,
            'n_entity_attributes'         => 0,
            'n_entity_attribute_values'   => 0,

            // sheets
            'n_sheets'                    => 0,

            // files
            'n_files'                     => 0,
            'n_files_not_found'           => 0,

            // columns
            'n_columns'                   => 0,
            'n_columns_skipped'           => 0,
        ]);

        $this->etlRun->save();
    }

    public function done()
    {
        $this->etlRun->save();
    }

    public function logError($msg)
    {
        $this->writeToLog("Error: {$msg}");
    }

    public function logWarning($msg)
    {
        $this->writeToLog("Warning: {$msg}");
    }

    public function logProgress($msg)
    {
        $this->writeToLog($msg);
    }

    private function writeToLog($msg)
    {
        Storage::disk('mcfs')->append("etl_logs/{$this->etlRun->uuid}.log", $msg);
    }
}