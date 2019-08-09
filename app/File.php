<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

    public function project()
    {
        $this->belongsTo(Project::class);
    }
}
