<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $casts = [
        'value' => 'array'
    ];
}
