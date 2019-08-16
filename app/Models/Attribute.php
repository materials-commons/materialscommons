<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $guarded = ['id', 'uuid'];

    public function attributable() {
        return $this->morphTo();
    }
}
