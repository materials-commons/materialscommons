<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

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
        return $this->belongsToMany(File::class, 'action2file');
    }

    public function entityStates()
    {
        return $this->belongsToMany(EntityState::class, 'action2entity_state');
    }
}
