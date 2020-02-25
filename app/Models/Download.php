<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Download extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    public function downloadable()
    {
        return $this->morphTo();
    }
}
