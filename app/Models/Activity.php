<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'activity2file')
                    ->withPivot('direction')
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entityStates()
    {
        return $this->belongsToMany(EntityState::class, 'activity2entity_state')
                    ->withPivot('direction')
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function entities()
    {
        return $this->hasManyThrough(Entity::class, EntityState::class);
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2activity', 'activity_id', 'experiment_id');
    }
}
