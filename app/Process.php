<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }
}
