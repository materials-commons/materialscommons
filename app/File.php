<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    public function project()
    {
        $this->belongsTo(Project::class);
    }
}
