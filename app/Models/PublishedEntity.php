<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class PublishedEntity extends Model
{
    use HasUUID;

    protected $guarded = ['id'];
}
