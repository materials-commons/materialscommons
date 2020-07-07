<?php

namespace App\Imports\Etl;

use App\Models\EtlRun;

class EtlState
{
    /** @var \App\Models\EtlRun */
    public $etlRun;

    public function __construct($ownerId)
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
    }

    public function done()
    {
        $this->etlRun->save();
    }
}