<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 *
 * @property integer $id
 * @property integer $owner_id
 * @property integer $n_activities
 * @property integer $n_activity_attributes
 * @property integer $n_activity_attribute_values
 * @property integer $n_entities
 * @property integer $n_entity_attributes
 * @property integer $n_entity_attribute_values
 * @property integer $n_sheets
 * @property integer $n_sheets_processed
 * @property integer $n_files
 * @property integer $n_files_not_found
 * @property string $files_not_found
 * @property integer $n_columns
 * @property integer $n_columns_skipped
 * @property string $columns_skipped
 */
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
        'n_sheets_processed'          => 'integer',

        // files
        'n_files'                     => 'integer',
        'n_files_not_found'           => 'integer',

        // columns
        'n_columns'                   => 'integer',
        'n_columns_skipped'           => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function etlable()
    {
        return $this->morphTo('etlable');
    }

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
