<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    //
    protected $dates = [
        'published_at',
        'privately_published_at'
    ];
}
