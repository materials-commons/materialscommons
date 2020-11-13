<?php

namespace App\Imports\Etl;

use App\Models\EtlRun;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EtlState
{
    /** @var \App\Models\EtlRun */
    public $etlRun;

    public function __construct($ownerId, $fileId)
    {
        $this->etlRun = EtlRun::make([
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
        $this->etlRun->files()->attach($fileId);
    }

    public function done()
    {
        $this->etlRun->save();
    }

    public function logError($msg)
    {
        $this->writeToLog($msg, "Error");
    }

    public function logWarning($msg)
    {
        $this->writeToLog($msg, "Warning");
    }

    public function logProgress($msg)
    {
        $this->writeToLog($msg, "");
    }

    private function writeToLog($msg, $msgType)
    {
        // Determine how much to indent and what the message type is to add
        $indentSize = strlen($msg) - strlen(ltrim($msg));
        $str = Str::of($msg)
                  ->ltrim()
                  ->prepend(strlen($msgType) > 0 ? "{$msgType}: " : "")
                  ->__toString();
        for ($i = 0; $i < $indentSize; $i++) {
            $str = ' '.$str;
        }
        Storage::disk('mcfs')->append("etl_logs/{$this->etlRun->uuid}.log", $str);
    }
}