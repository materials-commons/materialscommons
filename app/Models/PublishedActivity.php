<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class PublishedActivity extends Model
{
    use HasUUID;

    protected $guarded = ['id'];
}
