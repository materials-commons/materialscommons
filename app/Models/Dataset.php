<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasUUID;

    //
    protected $dates = [
        'published_at',
        'privately_published_at'
    ];
}
