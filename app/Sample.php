<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function project()
    {
        $this->belongsTo(Project::class);
    }
}
