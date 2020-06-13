<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class EntityState extends Model
{
    use HasUUID;

    protected $table = 'entity_states';
    protected $guarded = ['id'];

    protected $casts = [
        'current'   => 'boolean',
        'owner_id'  => 'integer',
        'entity_id' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'entity_state2file')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity2entity_state')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }
}
