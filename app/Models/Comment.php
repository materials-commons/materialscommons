<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Comment extends Model
{
    protected $guarded = ['id', 'uuid'];

    public function commentable()
    {
        return $this->morphTo();
    }
}
