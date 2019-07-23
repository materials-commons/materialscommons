<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }
}
