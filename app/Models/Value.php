<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasUUID;

    protected $casts = [
        'value' => 'array'
    ];
}
