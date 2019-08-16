<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
