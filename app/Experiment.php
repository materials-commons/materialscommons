<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
