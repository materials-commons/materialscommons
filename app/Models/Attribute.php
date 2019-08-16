<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public function attributable() {
        return $this->morphTo();
    }
}
