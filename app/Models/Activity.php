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

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'activity2file')
                    ->withPivot('direction')
                    ->withTimestamps();
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2activity', 'activity_id', 'dataset_id');
    }

    public function entityStates()
    {
        return $this->belongsToMany(EntityState::class, 'activity2entity_state')
                    ->withPivot('direction')
                    ->withTimestamps();
    }

//    public function entities()
//    {
//        return $this->hasManyThrough(Entity::class, EntityState::class);
//    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'activity2entity');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2activity', 'activity_id', 'experiment_id');
    }
}
