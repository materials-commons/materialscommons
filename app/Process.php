<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
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
}
