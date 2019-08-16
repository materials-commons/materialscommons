<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['id', 'uuid'];

    public function commentable()
    {
        return $this->morphTo();
    }
}
