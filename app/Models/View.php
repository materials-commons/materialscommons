<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class View extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    public function viewable()
    {
        return $this->morphTo();
    }
}
