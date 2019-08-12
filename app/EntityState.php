<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntityState extends Model
{
    protected $table = 'entity_states';
    protected $guarded = [];

    protected $casts = [
        'current' => 'boolean'
    ];


}
