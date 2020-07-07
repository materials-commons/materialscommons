<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class EtlRun extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'                    => 'integer',

        // activities
        'n_activities'                => 'integer',
        'n_activity_attributes'       => 'integer',
        'n_activity_attribute_values' => 'integer',

        // entities
        'n_entities'                  => 'integer',
        'n_entity_attributes'         => 'integer',
        'n_entity_attribute_values'   => 'integer',

        // sheets
        'n_sheets'                    => 'integer',

        // files
        'n_files'                     => 'integer',
        'n_files_not_found'           => 'integer',

        // columns
        'n_columns'                   => 'integer',
        'n_columns_skipped'           => 'integer',
    ];

    public function files()
    {
        return $this->morphToMany(File::class, 'item', 'item2file');
    }

    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'item', 'item2attribute');
    }

    public function activities()
    {
        return $this->morphToMany(Activity::class, 'item', 'item2activity');
    }

    public function entities()
    {
        return $this->morphToMany(Entity::class, 'item', 'item2entity');
    }
}
