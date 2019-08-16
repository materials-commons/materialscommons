<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];
    protected $casts = [
        'value' => 'array'
    ];
}
