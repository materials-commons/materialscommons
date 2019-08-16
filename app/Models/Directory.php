<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    use HasUUID;
    //
    protected $guarded = ['id', 'uuid'];

}
