<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    public function setDescriptionAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['description'] = '';
        } else {
            $this->attributes['description'] = $value;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entityStates()
    {
        return $this->hasMany(EntityState::class);
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2entity', 'entity_id', 'experiment_id');
    }

//    public function files() {
//        return $this->hasMany()
//    }
}
