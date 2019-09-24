<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    public function attributable() {
        return $this->morphTo();
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}
