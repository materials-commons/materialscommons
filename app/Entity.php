<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

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

//    public function files() {
//        return $this->hasMany()
//    }
}
